## The problem approach
The problem is to crawl a website and report the links inside it. The solution is to use a crawler to crawl the website and report the  links. The crawler will be a WordPress plugin that will be installed on the website and will crawl the website and report the SEO links in it.

## How it works
The plugin have a setting page to activate the crawler and a main page to show the results. The crawler will crawl the website and save the results in the database based on background process. The main page will show the results from the database inside WordPress dashboard.

## The results
The background process gives the user the ability to leave the page and the crawler will continue to crawl the website. The background process will be an action-scheduler task that will run asynchronous on saving settings and every 1-hour when the crawler activated. The results will be saved in the database to be shown in the main page.
Also, this will help to avoid the crawler to be blocked by the server just because action scheduler will work as a queue and will crawl the website in a slow rate.

## Technical details:

### Composer
Composer is a dependency manager for PHP. The plugin rely on the composer autoloader to install and load the required libraries and the plugin classes.

### Action scheduler
Action scheduler is a library that will help to run background process in WordPress. It will run the crawler in the background. 
Choosing action scheduler vs wp cron job was because of the following points:
1. WP Cron relies on visitor traffic to trigger scheduled events this dependency on traffic can lead missed executions if the site has low traffic.
2. Inaccurate timing because of the dependency on traffic.
3. WP Cron runs with each web request, which means it consumes server resources every time a visitor loads a page.

### Symfony Dom Crawler
Symfony Dom Crawler is easy to use, flexible, and well documented library. It will help to crawl the website and get the links from it. It can crawl the HTML string and extract the contents by CSS selectors or Xpath. And in our case, we need to extract the links from the website, so we can use it to select `<a>` tags and extract all info inside it.

### WordPress `wp_remote_*` functions
WordPress has a set of functions to make HTTP requests. It will help to make the HTTP requests to the website and get the HTML content of the website and no need to install HTTP libraries. `wp_remote_get()` will be used to get the HTML content of the website and pass it to the Dom Crawler to extract the links from it.

### WordPress Database
WordPress has a set of functions to make database queries. The plugin relies on `WP options API` for saving the results and getting the results from the database. 
Since the plugin only crawl the homepage only, it will result in a small data need to be saved to database and the options API will be enough for this case. In other cases, we can use custom tables to save the data.

### Sitemap HTML
Based on the crawl results, the plugin can display a sitemap.html page that contains all the links in the website. The sitemap.html page will rely on `WP Rewrite API` instead of saving a static HTML file. This will help to avoid the need to save a static HTML file and update it every time the website updated.

### GitHub actions
GitHub actions will be used to run to workflows:
1. The tests and check the code quality.
2. The plugin release package

Code quality tests and PHPUnit tests will be run on every push to the repository and on every pull request. The tests will be run using PHP 7.2, 7.4, and 8.0 versions. This ensures that the code quality is high and that the tests pass before the code is merged to the master branch and syncs the tests coverage to CodeGov.

## Room for improvement
1. The crawler can be improved to crawl the whole website and not only the homepage.
2. The crawler can be improved to crawl the website in a specific rate and not only every 1-hour.
3. The background task of the crawler that runs every 1-hour has to be disabled if there is active crawler.
4. The data came in the results can have extra information like http status codes and redirection count.
5. The data can be saved to custom tables instead of options API. This will allow saving crawls history and more data and can be much faster with optimized MySQL queries.
