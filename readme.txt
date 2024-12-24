=== Live Messages ===
Contributors: rbenjwal
Tags: messages, notifications, slack, api, live-updates
Requires at least: 5.8
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.2.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

A professional live messaging system with Slack integration and REST API support, designed specifically for Tantrakul.

== Description ==

Live Messages is a powerful WordPress plugin that enables real-time message broadcasting with seamless Slack integration. Perfect for announcements, updates, and important notifications.

= Key Features =

* Live Message Broadcasting: Post updates that appear instantly on your site
* Slack Integration: Automatic notifications to your Slack channel with custom branding
* REST API Support: Programmatic access for external systems
* Message Types: Support for different message types (info, success, warning, important)
* Secure API Access: API key authentication for secure external access
* Auto-refresh: Real-time updates in the admin dashboard
* Professional Styling: Clean, responsive design that works on all devices
* IP Tracking: Track message sources for enhanced security

= Usage =

**Shortcodes**

Display the full messages interface:
    [live_messages]

Show latest messages with type filter:
    [live_messages_latest count="5" type="important"]

**REST API**

Endpoint: `/wp-json/live-messages/v1/messages`

= Message Types =

* info: General information (Regular updates)
* success: Success messages (Positive notifications)
* warning: Warning messages (Important alerts)
* important: Critical messages (Urgent announcements)

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/live-messages`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > Live Messages to configure:
   * Main title and subtitle
   * Slack webhook URL
   * Generate API key for REST access

== Frequently Asked Questions ==

= How do I get a Slack webhook URL? =

You can create a Slack webhook URL by going to your Slack workspace's App Directory, creating a new app, and enabling Incoming Webhooks.

= Is the API secure? =

Yes, the API uses key-based authentication and includes IP tracking for enhanced security.

= Can I customize the message types? =

Currently, the plugin supports four predefined message types: info, success, warning, and important.

== Screenshots ==

1. Admin interface
2. Message creation form
3. API logs view
4. Slack notification example

== Changelog ==

= 1.2.0 =
* Added Slack integration with improved formatting
* Enhanced API security with key authentication
* Added auto-refresh functionality
* Improved admin interface
* Added IP tracking for API requests
* Added Tantrakul branding in Slack messages

= 1.1.0 =
* Added REST API support
* Added message types
* Improved database structure

= 1.0.0 =
* Initial release
* Basic messaging functionality

== Upgrade Notice ==

= 1.2.0 =
This version adds Slack integration, enhanced security features, and real-time updates. Please update your Slack webhook URL in the settings after upgrading.

== Privacy Policy ==

This plugin tracks IP addresses for API requests to enhance security. No personal data is shared with external services except when configured to send notifications to Slack.

== Additional Information ==

For support or feature requests, please visit [GitHub Issues](https://github.com/rbenjwal/live-messages/issues).

This plugin is developed and maintained by [Rajesh Benjwal](https://github.com/rbenjwal).
* First stable release
* Improved admin interface
* Consolidated settings menu
* Added message management dashboard
* Added Slack integration
* Added customizable titles
* Added comprehensive shortcode support

= 0.9.2-beta =
* Added customizable titles in settings
* Added Slack integration
* Added comprehensive shortcode documentation
* Added settings page for configuration

= 0.9.1-beta =
* Initial beta release
* Live updating messages
* Social sharing
* Message types with icons
* Pagination support

== Upgrade Notice ==
= 1.0.0 =
First stable release with improved admin interface and complete feature set
= 0.9.0-beta =
Initial beta release with core functionality
= 0.9.1-beta =
Added automatic refresh functionality
= 0.9.2-beta =
Added customizable titles in settings, Slack integration, comprehensive shortcode documentation, and settings page for configuration