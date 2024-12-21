# Live Messages

A WordPress plugin for displaying real-time messages and announcements with automatic refresh functionality.

## Features

- 🔄 Real-time message updates with automatic refresh
- 📱 Responsive design for all devices
- 🎯 Multiple display options:
  - Full message board `[live_messages]`
  - Compact ticker display `[live_messages_latest]`
- 🎨 Four message types with distinct styling:
  - Important
  - Warning
  - Success
  - Info
- 🔧 Customizable settings:
  - Refresh interval
  - Display preferences
  - Message styling
- 📱 Social media sharing integration
- 📊 Message pagination
- ⏱️ Human-readable timestamps

## Installation

1. Upload the plugin files to `/wp-content/plugins/live-messages`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure settings via 'Live Messages' in your admin menu

## Usage

### Basic Implementation

Add messages board to any page or post:
`[live_messages]`

Display latest messages in compact form:
`[live_messages_latest]`

### Advanced Options

The compact display shortcode accepts these parameters:

| Parameter  | Description                | Default | Options            |
|------------|----------------------------|---------|-------------------|
| type       | Filter by message type    | all     | important/warning/success/info |
| count      | Number of messages        | 1       | Any number        |
| words      | Word limit per message    | 30      | Any number        |
| show_date  | Display timestamp         | yes     | yes/no            |
| show_type  | Display message type      | yes     | yes/no            |

Example with parameters:
`[live_messages_latest type="important" count="2" words="50" show_date="yes" show_type="no"]`

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher

## Support

For support and feature requests, please use the [GitHub issues page](https://github.com/rajroshi/live-messages/issues).

## License

This project is licensed under the GPL v3 License - see the [LICENSE](LICENSE) file for details.
