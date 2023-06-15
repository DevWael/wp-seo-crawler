<?php

declare( strict_types=1 );

namespace DevWael\WpSeoCrawler\Storage;

/**
 * Interface StoragePortal
 *
 * @package DevWael\WpSeoCrawler\Storage
 */
interface DataStorage {
	/**
	 * Get the data from the cache storage.
	 *
	 * @param string $key the key of the cached data.
	 *
	 * @return array the cached data.
	 */
	public function get( string $key ): array;

	/**
	 * Save data to cache storage.
	 *
	 * @param string $key        the key of the cached data.
	 * @param array  $data       data to be cached.
	 *
	 * @return void
	 */
	public function set( string $key, array $data ): void;

	/**
	 * Purge the cached data.
	 *
	 * @param string $key the key of the cached data.
	 *
	 * @return void
	 */
	public function purge( string $key ): void;
}
