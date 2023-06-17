<?php

namespace WpSeoCrawler\Tests\Unit\Admin;

use DevWael\WpSeoCrawler\Admin\ReportView;
use DevWael\WpSeoCrawler\Storage\DataController;
use WpSeoCrawler\Tests\Unit\AbstractUnitTestCase;
use Brain\Monkey\Functions;

class ReportViewTest extends AbstractUnitTestCase {
	private function wp_list_table_mock(): \WP_List_Table {
		return \Mockery::mock( \WP_List_Table::class, [
			'singular' => 'item',
			'plural'   => 'items',
			'ajax'     => false,
		] );
	}

	private function data_controller_mock() {
		return \Mockery::mock( DataController::class );
	}

	private function links_data(): array {
		return [
			'links' => [
				[
					'found_at' => 'https://example.com',
					'href'     => 'https://example.com/page1',
					'text'     => 'text',
					'title'    => 'title',
					'_blank'   => false,
				],
			],
			'time'  => 1686952805,
		];
	}

	private function row_item(): array {
		return [
			'source'      => 'https://example.com',
			'links_found' => 'https://example.com/page1',
			'text'        => 'text',
			'title'       => 'title',
			'new_tab'     => 'No',
		];
	}

	private function empty_row_item(): array {
		return [];
	}

	public function test_empty_column_source(): void {
		$this->wp_list_table_mock();
		Functions\stubEscapeFunctions();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		$report_view = new ReportView();
		$actual      = $report_view->column_source( $this->empty_row_item() );
		$this->assertEquals( '', $actual );
	}

	public function test_column_source(): void {
		$this->wp_list_table_mock();
		Functions\stubEscapeFunctions();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		$report_view = new ReportView();
		$actual      = $report_view->column_source( $this->row_item() );
		$this->assertEquals( '<a href="https://example.com" target="_blank">https://example.com</a>', $actual );
	}

	public function test_column_links_found_empty(): void {
		$this->wp_list_table_mock();
		Functions\stubEscapeFunctions();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		$report_view = new ReportView();
		$actual      = $report_view->column_links_found( $this->empty_row_item() );
		$this->assertEquals( '', $actual );
	}

	public function test_column_links_found(): void {
		$this->wp_list_table_mock();
		Functions\stubEscapeFunctions();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		$report_view = new ReportView();
		$actual      = $report_view->column_links_found( $this->row_item() );
		$this->assertEquals( '<a href="https://example.com/page1" target="_blank">https://example.com/page1</a>', $actual );
	}

	public function test_column_text(): void {
		$this->wp_list_table_mock();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\stubEscapeFunctions();
		$report_view = new ReportView();
		$actual      = $report_view->column_text( $this->row_item() );
		$this->assertEquals( 'text', $actual );
	}

	public function test_column_text_empty(): void {
		$this->wp_list_table_mock();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\stubEscapeFunctions();
		$report_view = new ReportView();
		$actual      = $report_view->column_text( $this->empty_row_item() );
		$this->assertEquals( '', $actual );
	}

	public function test_column_title(): void {
		$this->wp_list_table_mock();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\stubEscapeFunctions();
		$report_view = new ReportView();
		$actual      = $report_view->column_title( $this->row_item() );
		$this->assertEquals( 'title', $actual );
	}

	public function test_column_title_empty(): void {
		$this->wp_list_table_mock();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\stubEscapeFunctions();
		$report_view = new ReportView();
		$actual      = $report_view->column_title( $this->empty_row_item() );
		$this->assertEquals( '', $actual );
	}

	public function test_column_new_tab(): void {
		$this->wp_list_table_mock();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\stubEscapeFunctions();
		$report_view = new ReportView();
		$actual      = $report_view->column_new_tab( $this->row_item() );
		$this->assertEquals( 'No', $actual );
	}

	public function test_column_new_tab_empty(): void {
		$this->wp_list_table_mock();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\stubEscapeFunctions();
		$report_view = new ReportView();
		$actual      = $report_view->column_new_tab( $this->empty_row_item() );
		$this->assertEquals( '', $actual );
	}

	public function test_columns_data_return_empty_array(): void {
		$this->wp_list_table_mock();
		$data_controller_mock = $this->data_controller_mock();
		$data_controller_mock->shouldReceive( 'update_url' );
		$data_controller_mock->shouldReceive( 'read' )->andReturn( [] );
		Functions\stubEscapeFunctions();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\expect( 'get_option' )->with( 'date_format' )->andReturn( 'd/m/Y' );
		Functions\expect( 'get_option' )->with( 'time_format' )->andReturn( 'g:i A' );
		Functions\expect( 'get_date_from_gmt' )->andReturn( 'date' );
		$report_view = new ReportView( $data_controller_mock );
		$actual      = $report_view->columns_data();
		$this->assertEmpty( $actual );
	}

	public function test_prepare_items_not_throw_exception(): void {
		try {
			$this->wp_list_table_mock();
			Functions\stubTranslationFunctions();
			Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
			Functions\expect( 'get_option' );
			Functions\stubEscapeFunctions();
			$report_view = new ReportView();
			$report_view->prepare_items();
		} catch ( \Exception $e ) {
			$this->fail( 'Exception should not be thrown' );
		}
		$this->assertTrue( true );
	}

	public function test_get_columns_return_array(): void {
		$this->wp_list_table_mock();
		Functions\stubTranslationFunctions();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\stubEscapeFunctions();
		$report_view = new ReportView();
		$actual      = $report_view->get_columns();
		$this->assertEquals( $actual, [
			'source'      => 'Source',
			'links_found' => 'Links Found',
			'title'       => 'Link Title',
			'text'        => 'Link Text',
			'new_tab'     => 'Open in new tab',
		] );
	}

	public function test_columns_data_return_array(): void {
		$this->wp_list_table_mock();
		$data_controller_mock = $this->data_controller_mock();
		$data_controller_mock->shouldReceive( 'update_url' );
		$data_controller_mock->shouldReceive( 'read' )->andReturn( $this->links_data() );
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\expect( 'get_option' )->with( 'date_format' )->andReturn( 'd/m/Y' );
		Functions\expect( 'get_option' )->with( 'time_format' )->andReturn( 'g:i A' );
		Functions\expect( 'get_date_from_gmt' )->andReturn( 'date' );
		$report_view = new ReportView( $data_controller_mock );
		$actual      = $report_view->columns_data();
		$this->assertEquals( [
			[
				'source'      => 'https://example.com',
				'links_found' => 'https://example.com/page1',
				'text'        => 'text',
				'title'       => 'title',
				'new_tab'     => 'No',
			],
		], $actual );
	}

	public function test_get_latest_update_return_empty_string(): void {
		$this->wp_list_table_mock();
		$data_controller_mock = $this->data_controller_mock();
		$data_controller_mock->shouldReceive( 'update_url' );
		$data_controller_mock->shouldReceive( 'read' )->andReturn( [] );
		Functions\stubEscapeFunctions();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\expect( 'get_option' )->andReturn( '' );
		$report_view = new ReportView();
		$actual      = $report_view->get_latest_update();
		$this->assertEmpty( $actual );
	}

	public function test_get_latest_update_return_date(): void {
		$this->wp_list_table_mock();
		$data_controller_mock = $this->data_controller_mock();
		$data_controller_mock->shouldReceive( 'update_url' );
		$data_controller_mock->shouldReceive( 'read' )->andReturn( $this->links_data() );
		Functions\stubEscapeFunctions();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\expect( 'get_option' )->with( 'date_format' )->andReturn( 'd/m/Y' );
		Functions\expect( 'get_option' )->with( 'time_format' )->andReturn( 'g:i A' );
		Functions\expect( 'get_date_from_gmt' )->andReturn( 'date' );
		$report_view = new ReportView( $data_controller_mock );
		$actual      = $report_view->get_latest_update();
		$this->assertEquals( 'date', $actual );
	}
}
