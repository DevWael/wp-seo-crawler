<?php

namespace DevWael\WpSeoCrawler\admin;

use WP_List_Table;
use DevWael\WpSeoCrawler\Storage\DataController;

class ReportView extends WP_List_Table {

	/**
	 * Data controller object.
	 *
	 * @var DataController
	 */
	private $data_controller;

	/**
	 * ReportView constructor.
	 *
	 * @param DataController|null $data_controller The data controller object.
	 */
	public function __construct( DataController $data_controller = null ) {
		$this->data_controller = $data_controller ?? new DataController();
		parent::__construct(
			[
				'singular' => 'item',
				'plural'   => 'items',
				'ajax'     => false,
			]
		);
	}

	/**
	 * Get the crawled links data
	 *
	 * @return array<string,string> links
	 */
	private function get_links(): array {
		$this->data_controller->update_url( \esc_url( \home_url() ) );

		return $this->data_controller->read();
	}

	/**
	 * Get latest update date.
	 *
	 * @return string The latest update date or empty string if no data.
	 */
	public function get_latest_update(): string {
		$data = $this->get_links();
		if ( ! empty( $data ) ) {
			return gmdate( 'd/m/Y - h:m A', $data['time'] );
		}

		return '';
	}

	/**
	 * Get the table columns data
	 *
	 * @return array<string,string> The table columns data
	 */
	private function columns_data(): array {
		$links = $this->get_links();
		if ( ! empty( $links['links'] ) ) {
			$data = [];
			foreach ( $links['links'] as $link ) {
				$data[] = [
					'source'      => $link['found_at'],
					'links_found' => $link['href'],
					'text'        => $link['text'],
					'title'       => $link['title'],
					'new_tab'     => $link['_blank'] ? 'Yes' : 'No',
				];
			}

			return $data;
		}

		return [];
	}

	/**
	 * Prepare the items for the table to process
	 */
	public function prepare_items(): void {
		$columns               = $this->get_columns();
		$this->_column_headers = [ $columns ];
		$this->items           = $this->columns_data();
	}

	/**
	 * Get the table columns
	 *
	 * @return array<string,string>
	 */
	public function get_columns(): array {
		return \apply_filters(
			'wpseoc_crawl_columns',
			[
				'source'      => \esc_html__( 'Source', 'wp-seo-crawler' ),
				'links_found' => \esc_html__( 'Links Found', 'wp-seo-crawler' ),
				'title'       => \esc_html__( 'Link Title', 'wp-seo-crawler' ),
				'text'        => \esc_html__( 'Link Text', 'wp-seo-crawler' ),
				'new_tab'     => \esc_html__( 'Open in new tab', 'wp-seo-crawler' ),
			]
		);
	}

	/**
	 * Get the table's column value for the 'page' column
	 *
	 * @param array $item data.
	 *
	 * @return string
	 */
	public function column_source( array $item ) {
		if ( isset( $item['source'] ) ) {
			return '<a href="' . esc_url( $item['source'] ) . '" target="_blank">' . esc_html( $item['source'] ) . '</a>';
		}

		return '';
	}

	/**
	 * Get the table's column value for the 'links_found' column
	 *
	 * @param array $item data.
	 *
	 * @return string
	 */
	public function column_links_found( array $item ) {
		if ( isset( $item['links_found'] ) ) {
			return '<a href="' . esc_url( $item['links_found'] ) . '" target="_blank">' . esc_html( $item['links_found'] ) . '</a>';
		}

		return '';
	}

	/**
	 * Get the table's column value for the 'text' column
	 *
	 * @param array $item data.
	 *
	 * @return string
	 */
	public function column_text( array $item ) {
		if ( isset( $item['text'] ) ) {
			return esc_html( $item['text'] );
		}

		return '';
	}

	/**
	 * Get the table's column value for the 'title' column
	 *
	 * @param array $item data.
	 *
	 * @return string
	 */
	public function column_title( array $item ) {
		if ( isset( $item['title'] ) ) {
			return esc_html( $item['title'] );
		}

		return '';
	}

	/**
	 * Get the table's column value for the 'new_tab' column
	 *
	 * @param array $item data.
	 *
	 * @return string
	 */
	public function column_new_tab( array $item ): string {
		if ( isset( $item['new_tab'] ) ) {
			return esc_html( $item['new_tab'] );
		}

		return '';
	}
}
