<?php
/*
Plugin Name: Live Messages
Description: Display live updating short messages like tweets
Version: 0.9.0-beta
Author: Rajesh Benjwal
Author URI: https://tantrakul.org
GitHub Plugin URI: rajroshi/live-messages
Plugin URI: https://github.com/rajroshi/live-messages
License: GPL v3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

// Include the update checker
require_once plugin_dir_path(__FILE__) . 'includes/plugin-update-checker/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// Set up the update checker with your correct GitHub repository
$updateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/rajroshi/live-messages/',
    __FILE__,
    'live-messages'
);

// Set the branch that contains the stable release
$updateChecker->setBranch('main');

// Optional: If you want to enable update checking for pre-release versions
$updateChecker->getVcsApi()->enableReleaseAssets();

if (!defined('ABSPATH')) exit;

// Define plugin constants
define('LIVE_MESSAGES_VERSION', '0.9.0-beta');
define('LIVE_MESSAGES_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('LIVE_MESSAGES_PLUGIN_URL', plugin_dir_url(__FILE__));

// Enqueue scripts and styles
function live_messages_enqueue_assets() {
    wp_enqueue_style(
        'live-messages-style',
        plugins_url('css/live-messages.css', __FILE__),
        array(),
        '1.0'
    );

    wp_enqueue_script(
        'live-messages-script',
        plugins_url('js/live-messages.js', __FILE__),
        array('jquery'),
        '1.0',
        true
    );

    wp_localize_script('live-messages-script', 'liveMessages', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('live_messages_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'live_messages_enqueue_assets');

// Create the database table on activation
function live_messages_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'live_messages';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        content text NOT NULL,
        author_id bigint(20) NOT NULL,
        type varchar(20) NOT NULL DEFAULT 'info',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'live_messages_activate');

// Register post type
function live_messages_post_type() {
    register_post_type('live_message', array(
        'labels' => array(
            'name' => 'Live Messages',
            'singular_name' => 'Live Message',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Message',
            'edit_item' => 'Edit Message',
            'new_item' => 'New Message',
            'view_item' => 'View Message',
            'search_items' => 'Search Messages',
            'not_found' => 'No messages found',
            'not_found_in_trash' => 'No messages found in Trash'
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor'),
        'menu_icon' => 'dashicons-format-chat',
        'show_in_rest' => true // Enable Gutenberg editor
    ));
}
add_action('init', 'live_messages_post_type');

// Handle message submission
function handle_submit_message() {
    try {
        check_ajax_referer('live_messages_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Only administrators can post messages');
            return;
        }

        $title = sanitize_text_field($_POST['title']);
        $message = sanitize_textarea_field($_POST['message']);
        $type = sanitize_text_field($_POST['type']);
        
        if (empty($title) || empty($message)) {
            wp_send_json_error('Title and message are required');
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'live_messages';
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'title' => $title,
                'content' => $message,
                'author_id' => get_current_user_id(),
                'type' => $type,
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%d', '%s', '%s')
        );

        if ($result === false) {
            wp_send_json_error('Database error: ' . $wpdb->last_error);
            return;
        }

        wp_send_json_success(array(
            'message' => 'Message saved successfully',
            'id' => $wpdb->insert_id
        ));

    } catch (Exception $e) {
        wp_send_json_error('Error: ' . $e->getMessage());
    }
}
add_action('wp_ajax_submit_message', 'handle_submit_message');

// Get messages for display
function handle_get_messages() {
    check_ajax_referer('live_messages_nonce', 'nonce');

    global $wpdb;
    $table_name = $wpdb->prefix . 'live_messages';
    
    // Pagination parameters
    $page = isset($_POST['page']) ? max(1, intval($_POST['page'])) : 1;
    $per_page = isset($_POST['per_page']) ? max(1, intval($_POST['per_page'])) : 10;
    $offset = ($page - 1) * $per_page;
    
    // Get total count
    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    $total_pages = ceil($total_items / $per_page);
    
    // Get paginated messages
    $messages = $wpdb->get_results($wpdb->prepare(
        "SELECT m.*, 
            IFNULL(u.display_name, u.user_nicename) as author_name,
            m.created_at as formatted_date
         FROM {$table_name} m 
         LEFT JOIN {$wpdb->users} u ON m.author_id = u.ID 
         ORDER BY m.created_at DESC 
         LIMIT %d OFFSET %d",
        $per_page,
        $offset
    ));

    // Format dates and ensure display names
    foreach ($messages as $message) {
        if ($message->author_name === 'rbenjwal') {
            $message->author_name = 'Rajesh Benjwal';
        }
        $message->formatted_date = mysql2date('F j, Y g:i a', $message->created_at);
    }

    wp_send_json_success(array(
        'messages' => $messages,
        'current_page' => $page,
        'total_pages' => $total_pages,
        'total_items' => $total_items
    ));
}
add_action('wp_ajax_get_messages', 'handle_get_messages');
add_action('wp_ajax_nopriv_get_messages', 'handle_get_messages');

// Shortcode
function live_messages_shortcode() {
    ob_start();
    ?>
    <div id="live-messages-container">
        <div class="live-messages-header">
            <h2 class="live-messages-title">तंत्रकुल समाचार</h2>
            <p class="live-messages-subtitle">Latest Updates & Announcements</p>
        </div>
        
        <?php if (current_user_can('manage_options')): ?>
            <div id="message-form" class="message-form">
                <h3>Post New Update</h3>
                <div class="message-form-content">
                    <div class="message-title-field">
                        <label for="message-title">Title</label>
                        <input type="text" id="message-title" placeholder="Enter message title">
                    </div>
                    <div class="message-type-selector">
                        <label for="message-type">Message Type</label>
                        <select id="message-type">
                            <option value="info">Information</option>
                            <option value="success">Success</option>
                            <option value="warning">Warning</option>
                            <option value="important">Important</option>
                        </select>
                    </div>
                    <div class="message-content-field">
                        <label for="new-message">Message</label>
                        <textarea id="new-message" rows="3" placeholder="What's happening?"></textarea>
                    </div>
                    <div class="message-form-footer">
                        <button id="submit-message" class="button button-primary">Post Update</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div id="messages-list">
            <!-- Messages will be loaded here via AJAX -->
            <div class="loading">Loading messages...</div>
        </div>
    </div>

    <?php if (current_user_can('manage_options')): ?>
        <script>
        // Debug information
        console.log('Live Messages initialized');
        console.log('AJAX URL:', '<?php echo admin_url('admin-ajax.php'); ?>');
        console.log('Current user can post:', '<?php echo current_user_can('manage_options') ? 'yes' : 'no'; ?>');
        </script>
    <?php endif; ?>
    <?php
    return ob_get_clean();
}
add_shortcode('live_messages', 'live_messages_shortcode');
  