# Live Messages WordPress Plugin

A WordPress plugin to display live messages and announcements with REST API support.

## Features
- Display live messages and announcements
- Support for different message types (info, success, warning, important)
- REST API support for creating messages
- Slack integration for notifications
- API key authentication for secure posting
- Shortcode support for displaying messages

## Installation
1. Download the plugin
2. Upload to your WordPress plugins directory
3. Activate the plugin through WordPress admin
4. Configure settings at Settings > Live Messages
5. Generate API key from settings page for REST API usage

## Usage

### Shortcodes
Display all messages:
[live_messages]

Display latest/important message:
[live_messages_latest type="important" count="1" words="30" show_date="yes" show_type="yes"]

### REST API

#### Authentication
- Generate API key from plugin settings page
- Use API key in X-Api-Key header for POST requests
- GET requests are public and don't require authentication

#### Endpoints

GET Messages:
GET /wp-json/live-messages/v1/messages

POST New Message:
POST /wp-json/live-messages/v1/messages
Headers:
- Content-Type: application/json
- X-Api-Key: YOUR_API_KEY

Request Body:
{
  "title": "Message Title",
  "content": "Message Content",
  "type": "info",
  "author_id": 1
}

### Message Types
- info (default) - For general announcements
- success - For positive updates
- warning - For cautionary notices
- important - For critical updates

## Requirements
- WordPress 5.0 or higher
- PHP 7.4 or higher

## Changelog

### 1.1.0
- Added REST API support with API key authentication
- Added author_id support for messages
- Added API key generation in settings
- Improved settings page layout

### 1.0.0
- Initial release
- Basic message functionality
- Slack integration
- Shortcode support

## License
GPL v3 or later