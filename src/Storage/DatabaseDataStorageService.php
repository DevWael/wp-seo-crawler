<?php

declare( strict_types=1 );

namespace DevWael\WpSeoCrawler\Storage;

/**
 * This class provides the caching functionality.
 *
 * @package DevWael\WpSeoCrawler\Storage
 */
class DatabaseDataStorageService implements DataStorage {

	/**
	 * The prefix of the cached data.
	 *
	 * @var string
	 */
	private $prefix = 'wpseoc_';

	/**
	 * Get the data from the cache storage.
	 *
	 * @param string $key the key of the cached data.
	 *
	 * @return array the cached data.
	 */
	public function get( string $key ): array {
		$key  = $this->prefix . $key;
		$data = \get_option( $key );
		// Check if the key exists in the cache.
		if ( $data && \is_array( $data ) ) {
			// If it does, return the cached data.
			return \apply_filters( 'wpseoc_get_cached_data', $data, $key );
		}

		// If it doesn't, return empty array.
		return \apply_filters( 'wpseoc_get_cached_data', [], $key );
	}

	/**
	 * Save data to cache storage.
	 *
	 * @param string $key        the key of the cached data.
	 * @param array  $data       data to be cached.
	 *
	 * @return void
	 */
	public function set( string $key, array $data ): void {
		$key = $this->prefix . $key;
		// Add the data to the cache with the given key.
		\update_option(
			$key,
			\apply_filters( 'wpseoc_set_cache_data', $data ),
			false
		);
	}

	/**
	 * Purge the cached data.
	 *
	 * @param string $key the key of the cached data.
	 *
	 * @return void
	 */
	public function purge( string $key ): void {
		$key = $this->prefix . $key;
		\delete_option( $key );
	}
}
