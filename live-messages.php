<?php
/**
 * Plugin Name: Live Messages
 * Plugin URI: https://github.com/rbenjwal/live-messages
 * Description: A professional live messaging system for Tantrakul with Slack integration and REST API support
 * Version: 1.2.0
 * Author: Rajesh Benjwal
 * Author URI: https://github.com/rbenjwal
 * License: GPL v3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: live-messages
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

// Prevent direct access
if (!defined('ABSPATH')) exit;

// Include Plugin Update Checker
require_once plugin_dir_path(__FILE__) . 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// Setup the update checker
$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/rbenjwal/live-messages/',
    __FILE__,
    'live-messages'
);

// Set the branch that contains the stable release
$myUpdateChecker->setBranch('main');

// Optional: If you're using a private repository, set the access token
// $myUpdateChecker->setAuthentication('your-token');

// Rest of your plugin code...
