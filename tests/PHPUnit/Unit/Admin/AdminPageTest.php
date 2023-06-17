<?php

namespace WpSeoCrawler\Tests\Unit\Admin;

use DevWael\WpSeoCrawler\Admin\AdminPage;
use DevWael\WpSeoCrawler\Admin\Request;
use WpSeoCrawler\Tests\Unit\AbstractUnitTestCase;
use Brain\Monkey\Functions;


class AdminPageTest extends AbstractUnitTestCase {

	private function admin_page_mock() {
		return \Mockery::mock( AdminPage::class )->makePartial();
	}

	public function test_admin_page(): void {
		Functions\expect( 'add_menu_page' )->once();
		Functions\expect( 'add_submenu_page' )->once();
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		try {
			$admin_page = $this->admin_page_mock();
			$admin_page->admin_page();
		} catch ( \Throwable $notExpected ) {
			$this->fail( $notExpected->getMessage() );
		}
		$this->assertTrue( true );
	}

	public function test_admin_notices_success(): void {
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\expect( 'sanitize_text_field' )->andReturnFirstArg();
		Functions\expect( 'wp_unslash' )->andReturnFirstArg();
		Functions\expect( 'add_settings_error' );
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		$request = \Mockery::mock( Request::class )->makePartial();
		$request->shouldReceive( 'get' )->andReturn( [
			'status' => 'success',
			'page'   => 'wp-seo-crawler-settings',
		] );

		$admin_page         = new AdminPage( $request );
		$status = $admin_page->admin_notices();
		$this->assertEquals( 'success', $status );
	}

	public function test_admin_notices_error_1(): void {
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\expect( 'sanitize_text_field' )->andReturnFirstArg();
		Functions\expect( 'wp_unslash' )->andReturnFirstArg();
		Functions\expect( 'add_settings_error' );
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		$request = \Mockery::mock( Request::class )->makePartial();
		$request->shouldReceive( 'get' )->andReturn( [
			'status' => 'error_1',
			'page'   => 'wp-seo-crawler-settings',
		] );

		$admin_page         = new AdminPage( $request );
		$status = $admin_page->admin_notices();
		$this->assertEquals( 'error_1', $status );
	}

	public function test_admin_notices_error_2(): void {
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\expect( 'sanitize_text_field' )->andReturnFirstArg();
		Functions\expect( 'wp_unslash' )->andReturnFirstArg();
		Functions\expect( 'add_settings_error' );
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		$request = \Mockery::mock( Request::class )->makePartial();
		$request->shouldReceive( 'get' )->andReturn( [
			'status' => 'error_2',
			'page'   => 'wp-seo-crawler-settings',
		] );

		$admin_page         = new AdminPage( $request );
		$status = $admin_page->admin_notices();
		$this->assertEquals( 'error_2', $status );
	}

	public function test_process_save_options_save_on(): void {
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\expect( 'admin_url' )->andReturn( 'https://example.com' );
		Functions\expect( 'add_query_arg' )->andReturn( 'https://example.com' );
		Functions\expect( 'current_user_can' )->andReturn( true );
		Functions\expect( 'wp_verify_nonce' )->andReturn( true );
		Functions\expect( 'update_option' )->andReturnFirstArg();
		Functions\expect( 'sanitize_text_field' )->andReturnFirstArg();
		Functions\expect( 'wp_unslash' )->andReturnFirstArg();
		Functions\expect( 'wp_die' )->andReturnFirstArg();
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		$request = \Mockery::mock( Request::class )->makePartial();
		$request->shouldReceive( 'post' )->andReturn( [
			'_wpnonce'            => 'e23h732',
			'wpseoc_crawl_status' => 'on',
		] );

		$admin_page         = $this->admin_page_mock();
		$reflectionProperty = new \ReflectionProperty( AdminPage::class, 'request' );
		$reflectionProperty->setAccessible( true );
		$reflectionProperty->setValue( $admin_page, $request );
		$admin_page->shouldReceive( 'terminate' )->andReturn( true );
		$admin_page->shouldReceive( 'start_instant_crawl' );
		$admin_page->shouldReceive( 'start_hourly_crawl' );
		$admin_page->process_save_options();
		$this->assertTrue( true );
	}

	public function test_process_save_options_save_off(): void {
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\expect( 'admin_url' )->andReturn( 'https://example.com' );
		Functions\expect( 'add_query_arg' )->andReturn( 'https://example.com' );
		Functions\expect( 'current_user_can' )->andReturn( true );
		Functions\expect( 'wp_verify_nonce' )->andReturn( true );
		Functions\expect( 'update_option' )->andReturnFirstArg();
		Functions\expect( 'sanitize_text_field' )->andReturnFirstArg();
		Functions\expect( 'wp_unslash' )->andReturnFirstArg();
		Functions\expect( 'wp_die' )->andReturnFirstArg();
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		$request = \Mockery::mock( Request::class )->makePartial();
		$request->shouldReceive( 'post' )->andReturn( [
			'_wpnonce'            => 'e23h732',
			'wpseoc_crawl_status' => 'off',
		] );
		$admin_page         = $this->admin_page_mock();
		$reflectionProperty = new \ReflectionProperty( AdminPage::class, 'request' );
		$reflectionProperty->setAccessible( true );
		$reflectionProperty->setValue( $admin_page, $request );
		$admin_page->shouldReceive( 'terminate' )->andReturn( true );
		$admin_page->shouldReceive( 'cancel_instant_crawl' );
		$admin_page->shouldReceive( 'cancel_hourly_crawl' );
		$admin_page->process_save_options();
		$this->assertTrue( true );
	}

	public function test_process_save_options_save_bad_data(): void {
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\expect( 'admin_url' )->andReturn( 'https://example.com' );
		Functions\expect( 'add_query_arg' )->andReturn( 'https://example.com' );
		Functions\expect( 'current_user_can' )->andReturn( true );
		Functions\expect( 'wp_verify_nonce' )->andReturn( true );
		Functions\expect( 'sanitize_text_field' )->andReturnFirstArg();
		Functions\expect( 'wp_unslash' )->andReturnFirstArg();
		Functions\expect( 'wp_die' )->andReturnFirstArg();
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		$request = \Mockery::mock( Request::class )->makePartial();
		$request->shouldReceive( 'post' )->andReturn( [
			'_wpnonce'            => 'e23h732',
			'wpseoc_crawl_status' => 'bad data',
		] );
		$admin_page         = $this->admin_page_mock( $request );
		$reflectionProperty = new \ReflectionProperty( AdminPage::class, 'request' );
		$reflectionProperty->setAccessible( true );
		$reflectionProperty->setValue( $admin_page, $request );
		$admin_page->shouldReceive( 'terminate' )->andReturn( true );
		$admin_page->process_save_options();
		$this->assertTrue( true );
	}

	public function test_process_save_options_without_errors(): void {
		Functions\expect( 'home_url' )->andReturn( 'https://example.com' );
		Functions\expect( 'admin_url' )->andReturn( 'https://example.com' );
		Functions\expect( 'add_query_arg' )->andReturn( 'https://example.com' );
		Functions\expect( 'wp_safe_redirect' );
		Functions\expect( 'current_user_can' )->andReturn( true );
		Functions\expect( 'wp_verify_nonce' )->andReturn( true );
		Functions\expect( 'sanitize_text_field' )->andReturnFirstArg();
		Functions\expect( 'wp_unslash' )->andReturnFirstArg();
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		$request = \Mockery::mock( Request::class )->makePartial();
		$request->shouldReceive( 'post' )->andReturn( [ '_wpnonce' => 'e23h732' ] );
		$admin_page         = $this->admin_page_mock( $request );
		$reflectionProperty = new \ReflectionProperty( AdminPage::class, 'request' );
		$reflectionProperty->setAccessible( true );
		$reflectionProperty->setValue( $admin_page, $request );
		$admin_page->shouldReceive( 'terminate' )->andReturn( true );
		$admin_page->process_save_options();
		$this->assertTrue( true );
	}

	public function test_save_bad_permission(): void {
		Functions\expect( 'sanitize_text_field' )->andReturnFirstArg();
		Functions\expect( 'wp_unslash' )->andReturnFirstArg();
		Functions\expect( 'wp_verify_nonce' )->andReturn( true );
		Functions\expect( 'current_user_can' )->with( 'manage_options' )->once()->andReturn( false );
		Functions\expect( 'wp_die' )->andReturnFirstArg();
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		$request = \Mockery::mock( Request::class );
		$request->shouldReceive( 'post' )->andReturn( [ '_wpnonce' => 'e23h732' ] );
		$admin_page         = $this->admin_page_mock( $request );
		$reflectionProperty = new \ReflectionProperty( AdminPage::class, 'request' );
		$reflectionProperty->setAccessible( true );
		$reflectionProperty->setValue( $admin_page, $request );
		$admin_page->process_save_options();
		$this->assertTrue( true );
	}

	public function test_check_nonce_result_exception(): void {
		Functions\expect( 'wp_verify_nonce' )->andReturn( false );
		Functions\expect( 'wp_die' )->andReturnFirstArg();
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		$request = \Mockery::mock( Request::class )->makePartial();
		$request->shouldReceive( 'post' )->andReturn( [ 'no_nonce' => 'e23h732' ] );
		$admin_page         = $this->admin_page_mock( $request );
		$reflectionProperty = new \ReflectionProperty( AdminPage::class, 'request' );
		$reflectionProperty->setAccessible( true );
		$reflectionProperty->setValue( $admin_page, $request );
		$admin_page->process_save_options();
		$this->assertTrue( true );
	}

	public function test_crawl_report_no_exception(): void {
		Functions\expect( 'home_url' );
		Functions\expect( 'get_option' );
		Functions\expect( 'get_admin_page_title' );
		Functions\expect( 'settings_errors' );
		Functions\expect( 'plugin_dir_path' )->andReturn( dirname( __DIR__, 4 ) . '/' );
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		\Mockery::mock( \WP_List_Table::class )->makePartial();
		$admin_page = new AdminPage();
		ob_start();
		$admin_page->crawl_report();
		$content = ob_get_clean();
		$this->assertStringStartsWith( '<div class="wrap">', $content );
	}

	public function test_render_no_exception(): void {
		Functions\expect( 'get_option' );
		Functions\expect( 'home_url' );
		Functions\expect( 'admin_url' );
		Functions\expect( 'get_admin_page_title' );
		Functions\expect( 'settings_errors' );
		Functions\expect( 'checked' );
		Functions\expect( 'wp_nonce_field' );
		Functions\expect( 'submit_button' );
		Functions\expect( 'plugin_dir_path' )->andReturn( dirname( __DIR__, 4 ) . '/' );
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		$admin_page = new AdminPage();
		ob_start();
		$admin_page->render();
		$content = ob_get_clean();
		$this->assertStringStartsWith( '<div class="wrap">', $content );
	}

	public function test_start_instant_crawl(): void {
		Functions\expect( 'home_url' );
		Functions\stubEscapeFunctions();
		Functions\expect( 'as_has_scheduled_action' )->andReturn( false );
		Functions\expect( 'as_enqueue_async_action' )->andReturn( 25 );

		$admin_page = new AdminPage();
		$id         = $admin_page->start_instant_crawl();
		$this->assertEquals( 25, $id );
	}

	public function test_start_instant_crawl_task_exist(): void {
		Functions\expect( 'home_url' );
		Functions\stubEscapeFunctions();
		Functions\expect( 'as_has_scheduled_action' )->andReturn( true );

		$admin_page = new AdminPage();
		$id         = $admin_page->start_instant_crawl();
		$this->assertEquals( 0, $id );
	}

	public function test_cancel_instant_crawl(): void {
		Functions\expect( 'home_url' );
		Functions\stubEscapeFunctions();
		Functions\expect( 'as_has_scheduled_action' )->andReturn( true );
		Functions\expect( 'as_unschedule_all_actions' )->andReturn( 25 );

		$admin_page = new AdminPage();
		ob_start();
		$admin_page->cancel_instant_crawl();
		$content = ob_get_clean();
		$this->assertIsString( $content );
	}

	public function test_start_hourly_crawl(): void {
		Functions\expect( 'home_url' );
		Functions\stubEscapeFunctions();
		Functions\expect( 'as_has_scheduled_action' )->andReturn( false );
		Functions\expect( 'as_schedule_recurring_action' )->andReturn( 25 );

		$admin_page = new AdminPage();
		$id         = $admin_page->start_hourly_crawl();
		$this->assertEquals( 25, $id );
	}

	public function test_cancel_hourly_crawl(): void {
		Functions\expect( 'home_url' );
		Functions\stubEscapeFunctions();
		Functions\expect( 'as_has_scheduled_action' )->andReturn( true );
		Functions\expect( 'as_unschedule_all_actions' )->andReturn( 25 );

		$admin_page = new AdminPage();
		ob_start();
		$admin_page->cancel_hourly_crawl();
		$content = ob_get_clean();
		$this->assertIsString( $content );
	}

	public function test_start_hourly_crawl_task_exist(): void {
		Functions\expect( 'home_url' );
		Functions\stubEscapeFunctions();
		Functions\expect( 'as_has_scheduled_action' )->andReturn( true );

		$admin_page = new AdminPage();
		$id         = $admin_page->start_hourly_crawl();
		$this->assertEquals( 0, $id );
	}
}
