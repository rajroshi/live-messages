=== Live Messages ===
Contributors: rbenjwal
Tags: messages, live updates, announcements, news ticker, status updates
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 0.9.1-beta
Requires PHP: 7.2
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Display live updating messages and announcements with automatic refresh and compact display options.

== Description ==
Live Messages allows you to display real-time updates and announcements on your WordPress site. Messages are automatically refreshed and can be shared on social media.

New in 0.9.1: Added compact display shortcode perfect for sidebars and small spaces!

Features:
* Live updating messages
* Two display options:
  - Full message board [live_messages]
  - Compact latest messages [live_messages_latest]
* Multiple message types (info, warning, success, important)
* Social sharing
* Pagination support
* Customizable display options

== Installation ==
1. Upload the plugin files to `/wp-content/plugins/live-messages`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use shortcodes to display messages:
   - [live_messages] for full message board
   - [live_messages_latest] for compact display

== Usage ==
= Basic Usage =
* [live_messages] - Displays full message board
* [live_messages_latest] - Shows latest message in compact form

= Compact Display Options =
[live_messages_latest] supports these parameters:
* type - Filter by message type (important/warning/success/info)
* count - Number of messages to show (default: 1)
* words - Word limit for content (default: 30)
* show_date - Show/hide date (yes/no)
* show_type - Show/hide message type (yes/no)

Example: [live_messages_latest type="important" count="2" words="50"]

== Changelog ==
= 0.9.1-beta =
* Added new shortcode [live_messages_latest] for compact message display
* Added customizable display options for compact messages
* Added message type styling and visual indicators
* Improved message formatting and readability
* Added human-readable timestamps
* Optimized database queries

= 0.9.0-beta =
* Initial beta release
* Live updating messages
* Social sharing
* Message types with icons
* Pagination support

== Upgrade Notice ==
= 0.9.1-beta =
New feature: Added compact message display shortcode perfect for sidebars and small spaces!