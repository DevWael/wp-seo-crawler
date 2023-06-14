<?php

namespace DevWael\WpSeoCrawler\Crawler;

use Symfony\Component\DomCrawler\Crawler;

class WebCrawler implements CrawlerEngine {
	/**
	 * Symfony DomCrawler object.
	 *
	 * @var Crawler
	 */
	private $crawler;

	/**
	 * The link string.
	 *
	 * @var string $url The link string.
	 */
	private $url;

	/**
	 * LinksCrawler constructor.
	 *
	 * @param Crawler|null $crawler The Symfony DomCrawler object.
	 */
	public function __construct( Crawler $crawler = null ) {
		$this->crawler = $crawler ?? new Crawler();
	}

	/**
	 * Crawl the URL and get all links.
	 *
	 * @return array the extracted links.
	 * @throws \RuntimeException If the page is not loaded.
	 */
	public function crawl(): array {
		$data     = [];
		$response = $this->get_request();
		if ( ! $this->is_response_ok( $response ) ) {
			throw new \RuntimeException( esc_html__( 'Failed to fetch that URL', 'wp-seo-crawler' ) );
		}
		$html = $this->get_response_body( $response );
		$this->add_html( $html );
		$links = $this->extract_links();
		foreach ( $links as $link ) {
			$data[] = $link;
		}

		return $data;
	}

	/**
	 * Add URL that will be crawled.
	 *
	 * @param string $url url to crawl.
	 *
	 * @return void
	 */
	public function set_url( string $url ): void {
		$this->url = $url;
	}

	/**
	 * Send HTTP GET request to url and get response.
	 *
	 * @return array The response.
	 * @throws \RuntimeException If the page is not loaded.
	 */
	private function get_request(): array {
		if ( ! isset( $this->url ) ) {
			throw new \RuntimeException( esc_html__( 'The url is not set', 'wp-seo-crawler' ) );
		}
		$response = \wp_remote_get( $this->url );
		if ( \is_wp_error( $response ) ) {
			throw new \RuntimeException( $response->get_error_message() );
		}

		return $response;
	}

	/**
	 * Check if the request returned with status code 200.
	 *
	 * @param array $response response returned from GET request.
	 *
	 * @return bool true if the response is ok.
	 */
	private function is_response_ok( array $response ): bool {
		$response_code = \wp_remote_retrieve_response_code( $response );

		return $response_code >= 200 && $response_code < 300;
	}

	/**
	 * Get response body from url.
	 *
	 * @param array|\WP_Error $response HTTP response.
	 *
	 * @return string The body of the response. Empty string if body is empty or incorrect parameter given.
	 */
	protected function get_response_body( $response ): string {
		return \wp_remote_retrieve_body( $response );
	}

	/**
	 * Extract links from the crawler object.
	 *
	 * @return array
	 */
	private function extract_links(): array {
		$links_data = [];
		$links      = $this->crawler->filter( 'a' );
		if ( ! empty( $links ) ) {
			foreach ( $links as $link ) {
				// Skip links that don't have href attribute.
				if ( ! $link->hasAttribute( 'href' ) ) {
					continue;
				}

				$href = $link->getAttribute( 'href' );

				// Skip empty links.
				if ( empty( $href ) ) {
					continue;
				}

				// Skip links that start with #.
				if ( $this->starts_with( $href, '#' ) ) {
					continue;
				}

				// Skip links that start with javascript.
				if ( $this->starts_with( $href, 'javascript' ) ) {
					continue;
				}

				// Skip links to the admin area.
				if ( $this->contains( $href, esc_url( admin_url() ) ) ) {
					continue;
				}

				// Skip links to the login page.
				if ( $this->contains( $href, esc_url( wp_login_url() ) ) ) {
					continue;
				}

				// Skip links to the logout page.
				if ( $this->contains( $href, esc_url( wp_logout_url() ) ) ) {
					continue;
				}

				// Skip links to the register page.
				if ( $this->contains( $href, esc_url( wp_registration_url() ) ) ) {
					continue;
				}

				// Skip links to the lost password page.
				if ( $this->contains( $href, esc_url( wp_lostpassword_url() ) ) ) {
					continue;
				}

				// Skip links to the home page.
				if ( $this->contains( $href, esc_url( home_url() ) ) ) {
					continue;
				}

				// Skip links to the current page.
				if ( $this->contains( $href, esc_url( $this->url ) ) ) {
					continue;
				}

				// Skip links from custom functionality.
				$skip = \apply_filters( 'wpseoc_skip_link', false, $href, $this->url, $this );
				if ( $skip ) {
					continue;
				}

				$links_data[] = [
					'href'   => $href,
					//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					'text'   => $link->textContent,
					'title'  => $link->getAttribute( 'title' ),
					'_blank' => $link->hasAttribute( 'target' ) && $link->getAttribute( 'target' ) === '_blank',
				];
			}
		}

		// Clear the crawler object to avoid memory leaks.
		$this->crawler->clear();

		return $links_data;
	}

	/**
	 * Check if the string starts with the given substring.
	 *
	 * @param string $haystack The string to search in.
	 * @param string $needle   The substring to search for.
	 *
	 * @return bool
	 */
	public function starts_with( string $haystack, string $needle ): bool {
		return strpos( $haystack, $needle ) === 0;
	}

	/**
	 * Check if the string contains the given substring.
	 *
	 * @param string $haystack The string to search in.
	 * @param string $needle   The substring to search for.
	 *
	 * @return bool
	 */
	public function contains( string $haystack, string $needle ): bool {
		return strpos( $haystack, $needle ) !== false;
	}

	/**
	 * Crawl the URL and get all links.
	 *
	 * @param string $html HTML content to crawl.
	 *
	 * @throws \RuntimeException If the page is not loaded.
	 */
	protected function add_html( string $html ): void {
		if ( empty( $html ) ) {
			throw new \RuntimeException( esc_html__( 'The page is not loaded.', 'wp-seo-crawler' ) );
		}
		// Add the html content to the crawler object.
		$this->crawler->addHtmlContent( $html );
	}
}
