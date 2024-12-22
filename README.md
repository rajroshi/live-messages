# Live Messages WordPress Plugin

## Description
Live Messages is a WordPress plugin that enables real-time message updates and announcements on your website. Perfect for displaying important notifications, updates, or any time-sensitive information to your visitors.

## Features
- Real-time message updates
- Multiple message types (Info, Success, Warning, Important)
- Responsive design for all devices
- Bulk message management
- Slack integration for notifications
- Shortcode support
- Latest message widget
- Custom styling options

## Installation
1. Download the plugin from GitHub
2. Upload to your WordPress site's `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Configure settings in the WordPress admin panel under 'Live Messages'

## Usage

### Basic Shortcode
To display all messages in a responsive layout:
[live_messages]

This will show:
- Message submission form (for administrators)
- All messages in chronological order
- Message type indicators
- Author and date information

### Latest Message Shortcode Examples

1. Display single latest message:
[live_messages_latest]

2. Show 3 most recent messages:
[live_messages_latest count="3"]

3. Display only important messages:
[live_messages_latest type="important"]

4. Show latest success message with date:
[live_messages_latest type="success" show_date="yes"]

5. Display warning message without type indicator:
[live_messages_latest type="warning" show_type="no"]

6. Limit message length to 20 words:
[live_messages_latest words="20"]

7. Complete example with all parameters:
[live_messages_latest count="2" type="info" words="25" show_date="yes" show_type="yes"]

### Message Types and Their Uses

1. Info (Blue)
   - General announcements
   - Regular updates
   - News items
   Example: "Our office will be open during regular hours"

2. Success (Green)
   - Positive announcements
   - Completed actions
   - Achievement notifications
   Example: "Successfully launched new website feature"

3. Warning (Yellow)
   - Cautionary notices
   - Important reminders
   - Temporary issues
   Example: "Site maintenance scheduled for tomorrow"

4. Important (Red)
   - Critical updates
   - Emergency notices
   - Urgent announcements
   Example: "Emergency server maintenance in progress"

### Admin Panel Features

1. Message Management
   - View all messages in a table format
   - Sort by date, type, or author
   - Bulk actions for multiple messages
   - Quick delete functionality

2. Bulk Actions
   - Select multiple messages using checkboxes
   - Apply actions to selected messages
   - Available actions: Delete

3. Message Creation
   - Title: Brief heading for the message
   - Content: Main message body
   - Type: Select message type
   - Auto-save feature
   - Preview before posting

### Slack Integration Setup

1. Create Slack Webhook:
   - Go to Slack Apps
   - Create New App
   - Enable Webhooks
   - Copy Webhook URL

2. Plugin Configuration:
   - Navigate to Live Messages settings
   - Paste Webhook URL
   - Test connection
   - Save settings

3. Notification Features:
   - Real-time message posting to Slack
   - Customizable message format
   - Type-based notifications
   - Author attribution

### Customization

1. CSS Classes for Styling:
   - .live-messages-container (Main container)
   - .message-type (Type indicators)
   - .message-title (Message titles)
   - .message-content (Message body)
   - .message-meta (Date and author info)

2. Message Display Options:
   - Adjustable message count
   - Customizable word limit
   - Toggle date display
   - Toggle type indicators

## Changelog

### 1.0.1
- Enhanced frontend shortcode display with responsive design
- Fixed mobile layout issues
- Improved admin panel table layout
- Added bulk actions functionality
- Fixed overflow issues
- Added proper spacing and padding

### 1.0.0
- Initial release
- Basic message functionality
- Slack integration
- Shortcode support

## Support
- GitHub Issues: https://github.com/rajroshi/live-messages/issues
- Author: Rajesh Benjwal
- Website: https://tantrakul.org

## License
GPL v3 - See LICENSE file for details