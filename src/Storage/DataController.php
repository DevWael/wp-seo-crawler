<?php

namespace DevWael\WpSeoCrawler\Storage;

/**
 * This class provides the data handling functionality.
 *
 * @package DevWael\WpSeoCrawler\Storage
 */
class DataController {

	/**
	 * Object of database storage service.
	 *
	 * @var DatabaseDataStorageService|DataStorage
	 */
	private $database_storage_service;

	/**
	 * The url.
	 *
	 * @var string|null
	 */
	private $url;

	/**
	 * DataController constructor.
	 *
	 * @param DataStorage|null $database_storage_service The database storage service.
	 */
	public function __construct( DataStorage $database_storage_service = null ) {
		$this->database_storage_service = $database_storage_service ?? new DatabaseDataStorageService();
		$this->update_url();
	}

	/**
	 * Update url property in the object.
	 *
	 * @param string $url The url to update.
	 */
	public function update_url( string $url = '' ): void {
		$this->url = \esc_url( $url ?: \home_url() );
	}

	/**
	 * Generate the key for the data.
	 *
	 * @param string $url The url.
	 *
	 * @return string The generated key.
	 */
	private function generate_key( string $url ): string {
		return \md5( $url );
	}

	/**
	 * Escape the data.
	 *
	 * @param array $data The data returned from the crawler.
	 *
	 * @return array The escaped data.
	 */
	private function escape_data( array $data ): array {
		return array_map(
			static function ( $link ) {
				return [
					'found_at' => esc_url( $link['found_at'] ),
					'href'     => esc_url( $link['href'] ),
					'text'     => esc_html( $link['text'] ),
					'title'    => esc_html( $link['title'] ),
					'_blank'   => (bool) $link['_blank'],
				];
			},
			$data
		);
	}

	/**
	 * Save the data to the database.
	 *
	 * @param array $data The data returned from the crawler.
	 *
	 * @return void
	 */
	public function save( array $data ): void {
		$key  = $this->generate_key( $this->url );
		$data = [
			'links' => $this->escape_data( $data ),
			'time'  => \time(),
		];
		$this->database_storage_service->set( $key, $data );
	}

	/**
	 * Read the data from the database.
	 *
	 * @return array The data.
	 */
	public function read(): array {
		$key = $this->generate_key( $this->url );

		return $this->database_storage_service->get( $key );
	}

	/**
	 * Purge the data from the database.
	 *
	 * @return void
	 */
	public function delete(): void {
		$key = $this->generate_key( $this->url );
		$this->database_storage_service->purge( $key );
	}
}
