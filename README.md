# API Theme for WordPress

This WordPress theme is designed to function as a backend API for a blog. It provides custom endpoints for retrieving recent posts and searching posts, with built-in pagination support.

## Features

- Custom REST API endpoints
- Pagination support
- CORS enabled
- Featured image support

## Installation

1. Download the theme files and place them in your `wp-content/themes` directory.
2. Activate the theme through the WordPress admin panel.

## Usage

### Recent Posts Endpoint

GET `/wp-json/blog/v1/recent-posts`

Query parameters:
- `per_page`: Number of posts per page (default: 5)
- `page`: Page number (default: 1)

### Search Endpoint

GET `/wp-json/blog/v1/search`

Query parameters:
- `term`: Search term (required)
- `per_page`: Number of posts per page (default: 10)
- `page`: Page number (default: 1)

## Pagination

Both endpoints support pagination. The response headers include:

- `X-WP-Total`: Total number of items
- `X-WP-TotalPages`: Total number of pages

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the GPL-2.0+ License.
