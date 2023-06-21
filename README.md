# WP Seo Crawler
WordPress plugin that crawl your website to report seo links.

[![PHP Tests](https://github.com/DevWael/wp-seo-crawler/actions/workflows/php-tests.yml/badge.svg?branch=master)](https://github.com/DevWael/wp-seo-crawler/actions/workflows/php-tests.yml)
[![codecov](https://codecov.io/gh/DevWael/wp-seo-crawler/branch/master/graph/badge.svg?token=Z4OMDM6H5M)](https://codecov.io/gh/DevWael/wp-seo-crawler)

## Installation
1. Download the plugin zip file from the releases page.
2. Upload the plugin files to the `/wp-content/plugins/` directory and unzip it, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress.
4. Use the Seo Crawler menu page to configure the plugin and see crawl results.

## Usage
1. Go to Seo Crawler setting menu page.
2. Choose to activate the crawler and click save.
3. Wait for the crawler to finish.
4. Go to Seo Crawler main menu page to see the results.
5. The crawler will crawl the homepage every 1-hour and update.

## Minimum requirements
1. PHP 7.2 or greater.
2. WordPress 5.0 or greater.

## Development
1. Clone the repo.
2. Run `composer install` to install the required and developer dependencies.

## Testing
1. Run `composer test:coverage` to run the tests with coverage report.
2. Run `composer phpcs` to run the code sniffer.
3. Run `composer phpcs:fix` to fix the code sniffer errors.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[GPL v3 or later](https://www.gnu.org/licenses/gpl-3.0.html)
