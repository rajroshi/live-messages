=== Live Messages ===
Contributors: rbenjwal
Tags: messages, live updates, announcements
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 0.9.2-beta
Requires PHP: 7.2
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Display live updating messages and announcements with automatic refresh functionality.

== Description ==
Live Messages allows you to display real-time updates and announcements on your WordPress site. Messages are automatically refreshed and can be shared on social media.

= Available Shortcodes =

1. Full Message Board:
`[live_messages]`
Displays complete message board with all features including admin posting form and pagination.

2. Compact Display:
`[live_messages_latest]`
Shows latest messages in a compact format. Accepts the following parameters:

* type - Filter by message type (important/warning/success/info)
* count - Number of messages to display (default: 1)
* words - Word limit per message (default: 30)
* show_date - Display timestamp (yes/no, default: yes)
* show_type - Display message type (yes/no, default: yes)

= Shortcode Examples =

1. Basic implementation:
`[live_messages_latest]`
Shows single latest message with default settings

2. Show multiple important messages:
`[live_messages_latest type="important" count="3"]`
Displays 3 latest important messages

3. Custom word limit:
`[live_messages_latest type="warning" count="2" words="100"]`
Shows 2 warning messages with 100-word limit

4. Hide date and type:
`[live_messages_latest show_date="no" show_type="no"]`
Displays latest message without date and type indicators

== Installation ==
1. Upload the plugin files to `/wp-content/plugins/live-messages`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the shortcode [live_messages] to display the messages
4. Configure settings under Settings → Live Messages

== Changelog ==
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
= 0.9.0-beta =
Initial beta release with core functionality
= 0.9.1-beta =
Added automatic refresh functionality
= 0.9.2-beta =
Added customizable titles in settings, Slack integration, comprehensive shortcode documentation, and settings page for configuration