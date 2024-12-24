# Live Messages for WordPress

A professional live messaging system with Slack integration and REST API support, designed specifically for Tantrakul. This plugin enables real-time message broadcasting with seamless Slack integration, perfect for announcements, updates, and important notifications.

## Features

- Live Message Broadcasting: Post updates that appear instantly on your site
- Slack Integration: Automatic notifications to your Slack channel with custom branding
- REST API Support: Programmatic access for external systems
- Message Types: Support for different message types (info, success, warning, important)
- Secure API Access: API key authentication for secure external access
- Auto-refresh: Real-time updates in the admin dashboard
- Professional Styling: Clean, responsive design that works on all devices
- IP Tracking: Track message sources for enhanced security

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- Slack Webhook URL (for Slack integration)

## Installation

1. Upload the plugin files to `/wp-content/plugins/live-messages`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > Live Messages to configure:
   - Main title and subtitle
   - Slack webhook URL
   - Generate API key for REST access

## Usage

### Admin Interface

1. Navigate to Live Messages in WordPress admin
2. Configure general settings and Slack integration
3. Generate API key for external access
4. Monitor API logs in real-time

### Shortcodes

Display the full messages interface:

    [live_messages]

Show latest messages with type filter:

    [live_messages_latest count="5" type="important"]

### REST API

Endpoint: `/wp-json/live-messages/v1/messages`

POST Request Example:

    curl -X POST \
      https://your-site.com/wp-json/live-messages/v1/messages \
      -H 'Content-Type: application/json' \
      -H 'X-Api-Key: your-api-key' \
      -d '{
        "title": "Message Title",
        "content": "Message Content",
        "type": "info",
        "author_id": 1
      }'

### Message Types

| Type      | Description          | Use Case             |
|-----------|---------------------|---------------------|
| info      | General information | Regular updates     |
| success   | Success messages    | Positive notifications |
| warning   | Warning messages    | Important alerts    |
| important | Critical messages   | Urgent announcements |

## Auto Updates

The plugin includes automatic update functionality through GitHub releases. Updates will be notified in your WordPress admin panel.

## Changelog

### 1.2.0
- Added Slack integration with improved formatting
- Enhanced API security with key authentication
- Added auto-refresh functionality
- Improved admin interface
- Added IP tracking for API requests
- Added Tantrakul branding in Slack messages

### 1.1.0
- Added REST API support
- Added message types
- Improved database structure

### 1.0.0
- Initial release
- Basic messaging functionality

## Security

- API key authentication for REST endpoints
- IP tracking for all API requests
- Secure Slack webhook integration
- WordPress nonce verification
- Input sanitization and validation

## License

This plugin is licensed under the GNU General Public License v3.0 - see the [LICENSE](LICENSE) file for details.

## Support

For support, please:
1. Check the documentation
2. Visit [GitHub Issues](https://github.com/rbenjwal/live-messages/issues)
3. Contact the author directly

## Credits

Developed by [Rajesh Benjwal](https://github.com/rbenjwal)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the Project
2. Create your Feature Branch
3. Commit your Changes
4. Push to the Branch
5. Open a Pull Request

## Contact

Rajesh Benjwal - [GitHub Profile](https://github.com/rbenjwal)

Project Link: [https://github.com/rbenjwal/live-messages](https://github.com/rbenjwal/live-messages)