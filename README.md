# Live Messages

A WordPress plugin for displaying real-time messages and announcements with automatic refresh functionality.

## Usage

### Available Shortcodes

#### 1. Full Message Board
`[live_messages]`

Displays the complete message board with:
- All messages with pagination
- Message submission form (admin only)
- Full styling and formatting
- Social sharing options

#### 2. Compact Display
`[live_messages_latest]`

##### Parameters
| Parameter  | Description                | Default | Options            |
|------------|----------------------------|---------|-------------------|
| type       | Filter by message type    | all     | important/warning/success/info |
| count      | Number of messages        | 1       | Any number        |
| words      | Word limit per message    | 30      | Any number        |
| show_date  | Display timestamp         | yes     | yes/no            |
| show_type  | Display message type      | yes     | yes/no            |

### Example Usage

1. **Basic Implementation**
`[live_messages_latest]`
Shows single latest message with default settings

2. **Multiple Important Messages**
`[live_messages_latest type="important" count="3"]`
Displays 3 latest important messages

3. **Custom Word Limit**
`[live_messages_latest type="warning" count="2" words="100"]`
Shows 2 warning messages with 100-word limit

4. **Without Date and Type**
`[live_messages_latest show_date="no" show_type="no"]`
Displays latest message without date and type indicators

## Requirements
- WordPress 5.0 or higher
- PHP 7.2 or higher

## Support
For support and feature requests, please use the [GitHub issues page](https://github.com/rajroshi/live-messages/issues).

## License
This project is licensed under the GPL v3 License - see the [LICENSE](LICENSE) file for details.
