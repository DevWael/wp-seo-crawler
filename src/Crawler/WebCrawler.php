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
	 * @param Crawler $crawler The Symfony DomCrawler object.
	 * @param string  $url     The link string.
	 */
	public function __construct( Crawler $crawler, string $url ) {
		$this->crawler = $crawler;
		$this->url     = $url;
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
	 * Send HTTP GET request to url and get response.
	 *
	 * @return array The response.
	 * @throws \RuntimeException If the page is not loaded.
	 */
	private function get_request(): array {
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
	 * @return string The body of the response. Empty string if no body or incorrect parameter given.
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
				// Skip links that start with #.
				if ( 0 === strpos( $href, '#' ) ) {
					continue;
				}

				$links_data[] = [
					'href'  => $href,
					//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					'text'  => $link->textContent,
					'title' => $link->getAttribute( 'title' ),
				];
			}
		}

		// Clear the crawler object to avoid memory leaks.
		$this->crawler->clear();

		return $links_data;
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
