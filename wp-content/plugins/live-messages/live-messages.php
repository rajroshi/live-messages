<?php
/*
Plugin Name: Live Messages
Description: Display live updating short messages like tweets
Version: 0.9.2-beta
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
$updateChecker->getVcsApi()->enableReleaseAssets();
$updateChecker->setAuthentication('your-github-token'); // Optional: for private repos

if (!defined('ABSPATH')) exit;

// Define plugin constants
define('LIVE_MESSAGES_VERSION', '0.9.2-beta');
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

        if ($result !== false) {
            // Send to Slack
            notify_slack($message, $type, $title);
            
            wp_send_json_success(array(
                'message' => 'Message saved successfully',
                'id' => $wpdb->insert_id
            ));
        }

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
            <h2 class="live-messages-title"><?php echo esc_html(get_option('live_messages_main_title', 'तंत्रकुल समाचार')); ?></h2>
            <p class="live-messages-subtitle"><?php echo esc_html(get_option('live_messages_subtitle', 'Latest Updates & Announcements')); ?></p>
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

// Add new shortcode for latest/important message
function live_messages_latest_shortcode($atts) {
    // Parse attributes
    $atts = shortcode_atts(array(
        'type' => '',         // empty for any type, or 'important', 'warning', etc.
        'count' => 1,         // number of messages to show
        'words' => 30,        // word limit for content
        'show_date' => 'yes', // yes/no
        'show_type' => 'yes'  // yes/no
    ), $atts);

    global $wpdb;
    $table_name = $wpdb->prefix . 'live_messages';
    
    // Build query based on type
    $where_clause = '';
    if (!empty($atts['type'])) {
        $where_clause = $wpdb->prepare(" WHERE type = %s", $atts['type']);
    }

    // Get latest message(s)
    $messages = $wpdb->get_results($wpdb->prepare(
        "SELECT m.*, IFNULL(u.display_name, u.user_nicename) as author_name 
         FROM {$table_name} m 
         LEFT JOIN {$wpdb->users} u ON m.author_id = u.ID 
         {$where_clause}
         ORDER BY created_at DESC 
         LIMIT %d",
        intval($atts['count'])
    ));

    if (empty($messages)) {
        return '<div class="live-messages-latest empty">No messages found</div>';
    }

    ob_start();
    ?>
    <div class="live-messages-latest">
        <?php foreach ($messages as $message): ?>
            <div class="latest-message type-<?php echo esc_attr($message->type); ?>">
                <h4 class="message-title"><?php echo esc_html($message->title); ?></h4>
                
                <div class="message-content">
                    <?php 
                    $content = wp_trim_words($message->content, $atts['words'], '...');
                    echo wp_kses_post($content);
                    ?>
                </div>
                
                <?php if ($atts['show_type'] === 'yes'): ?>
                    <span class="message-type"><?php echo esc_html(ucfirst($message->type)); ?></span>
                <?php endif; ?>
                
                <?php if ($atts['show_date'] === 'yes'): ?>
                    <span class="message-date">
                        <?php echo esc_html(human_time_diff(strtotime($message->created_at), current_time('timestamp'))); ?> ago
                    </span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('live_messages_latest', 'live_messages_latest_shortcode');

// Add specific styles for the latest message display
function live_messages_latest_styles() {
    ?>
    <style>
        .live-messages-latest {
            margin: 10px 0;
            font-size: 14px;
        }
        .live-messages-latest .latest-message {
            padding: 10px 15px;
            border-radius: 4px;
            border-left: 4px solid #ccc;
            background: #f9f9f9;
            margin-bottom: 10px;
        }
        .live-messages-latest .message-title {
            margin: 0 0 5px 0;
            font-size: 16px;
            font-weight: 600;
        }
        .live-messages-latest .message-content {
            margin-bottom: 8px;
        }
        .live-messages-latest .message-type,
        .live-messages-latest .message-date {
            font-size: 12px;
            color: #666;
            margin-right: 10px;
        }
        /* Message type styles */
        .live-messages-latest .type-important {
            border-left-color: #dc3545;
            background: #fff8f8;
        }
        .live-messages-latest .type-warning {
            border-left-color: #ffc107;
            background: #fffbf0;
        }
        .live-messages-latest .type-success {
            border-left-color: #28a745;
            background: #f0fff4;
        }
        .live-messages-latest .type-info {
            border-left-color: #17a2b8;
            background: #f0f9fc;
        }
    </style>
    <?php
}
add_action('wp_head', 'live_messages_latest_styles');

// Add Slack settings to WordPress admin
function live_messages_add_settings() {
    add_options_page(
        'Live Messages Settings',
        'Live Messages',
        'manage_options',
        'live-messages-settings',
        'live_messages_settings_page'
    );

    register_setting('live-messages', 'live_messages_slack_webhook');
    register_setting('live-messages', 'live_messages_main_title');
    register_setting('live-messages', 'live_messages_subtitle');
}
add_action('admin_menu', 'live_messages_add_settings');

function live_messages_settings_page() {
    ?>
    <div class="wrap">
        <h2>Live Messages Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('live-messages'); ?>
            <table class="form-table">
                <tr>
                    <th>Main Title</th>
                    <td>
                        <input type="text" 
                               name="live_messages_main_title" 
                               value="<?php echo esc_attr(get_option('live_messages_main_title', 'तंत्रकुल समाचार')); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th>Subtitle</th>
                    <td>
                        <input type="text" 
                               name="live_messages_subtitle" 
                               value="<?php echo esc_attr(get_option('live_messages_subtitle', 'Latest Updates & Announcements')); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th>Slack Webhook URL</th>
                    <td>
                        <input type="text" 
                               name="live_messages_slack_webhook" 
                               value="<?php echo esc_attr(get_option('live_messages_slack_webhook')); ?>" 
                               class="regular-text">
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function notify_slack($message, $type, $title) {
    $webhook_url = get_option('live_messages_slack_webhook');
    if (empty($webhook_url)) return;

    $color = '#17a2b8'; // default info color
    switch ($type) {
        case 'important':
            $color = '#dc3545';
            break;
        case 'warning':
            $color = '#ffc107';
            break;
        case 'success':
            $color = '#28a745';
            break;
    }

    $data = array(
        'attachments' => array(
            array(
                'color' => $color,
                'title' => $title,
                'text' => $message,
                'fields' => array(
                    array(
                        'title' => 'Type',
                        'value' => ucfirst($type),
                        'short' => true
                    )
                )
            )
        )
    );

    wp_remote_post($webhook_url, array(
        'body' => json_encode($data),
        'headers' => array('Content-Type' => 'application/json'),
    ));
}

// Add admin menu for messages
function live_messages_admin_menu() {
    add_menu_page(
        'Live Messages',
        'Live Messages',
        'manage_options',
        'live-messages',
        'live_messages_admin_page',
        'dashicons-format-chat',
        30
    );

    // Add submenu items
    add_submenu_page(
        'live-messages',
        'All Messages',
        'All Messages',
        'manage_options',
        'live-messages',
        'live_messages_admin_page'
    );

    add_submenu_page(
        'live-messages',
        'Settings',
        'Settings',
        'manage_options',
        'live-messages-settings',
        'live_messages_settings_page'
    );

    // Register settings here
    register_setting('live-messages', 'live_messages_slack_webhook');
    register_setting('live-messages', 'live_messages_main_title');
    register_setting('live-messages', 'live_messages_subtitle');
}
add_action('admin_menu', 'live_messages_admin_menu');

// Admin page display
function live_messages_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'live_messages';

    // Handle deletion
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $wpdb->delete($table_name, array('id' => $id), array('%d'));
        echo '<div class="notice notice-success"><p>Message deleted successfully!</p></div>';
    }

    // Get messages with pagination
    $page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $items_per_page = 20;
    $offset = ($page - 1) * $items_per_page;

    $messages = $wpdb->get_results($wpdb->prepare(
        "SELECT m.*, IFNULL(u.display_name, u.user_nicename) as author_name 
         FROM $table_name m 
         LEFT JOIN {$wpdb->users} u ON m.author_id = u.ID 
         ORDER BY created_at DESC 
         LIMIT %d OFFSET %d",
        $items_per_page,
        $offset
    ));

    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    $total_pages = ceil($total_items / $items_per_page);
    ?>
    <div class="wrap">
        <h1>Live Messages</h1>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Type</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?php echo esc_html($message->id); ?></td>
                        <td><?php echo esc_html($message->title); ?></td>
                        <td><?php echo esc_html($message->content); ?></td>
                        <td>
                            <span class="message-type <?php echo esc_attr($message->type); ?>">
                                <?php echo esc_html(ucfirst($message->type)); ?>
                            </span>
                        </td>
                        <td><?php echo esc_html($message->author_name); ?></td>
                        <td><?php echo esc_html(date('Y-m-d H:i:s', strtotime($message->created_at))); ?></td>
                        <td>
                            <a href="<?php echo wp_nonce_url(add_query_arg(array('action' => 'delete', 'id' => $message->id)), 'delete_message_' . $message->id); ?>" 
                               onclick="return confirm('Are you sure you want to delete this message?')" 
                               class="button button-small button-link-delete">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
        // Pagination
        echo '<div class="tablenav bottom">';
        echo '<div class="tablenav-pages">';
        echo paginate_links(array(
            'base' => add_query_arg('paged', '%#%'),
            'format' => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => $total_pages,
            'current' => $page
        ));
        echo '</div>';
        echo '</div>';
        ?>
    </div>

    <style>
        .message-type {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
        }
        .message-type.important {
            background: #dc3545;
            color: white;
        }
        .message-type.warning {
            background: #ffc107;
            color: black;
        }
        .message-type.success {
            background: #28a745;
            color: white;
        }
        .message-type.info {
            background: #17a2b8;
            color: white;
        }
    </style>
    <?php
}
  