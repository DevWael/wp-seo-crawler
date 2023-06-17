<?php

namespace WpSeoCrawler\Tests\Unit\BackgroundWorkers;

use DevWael\WpSeoCrawler\BackgroundWorkers\ImmediateCrawl;
use WpSeoCrawler\Tests\Unit\AbstractUnitTestCase;
use Brain\Monkey\Functions;
class ImmediateCrawlTest extends AbstractUnitTestCase {

	public function test_no_url_throw_exception(): void {
		Functions\expect( 'home_url' )->once();
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		$immediate_task = new ImmediateCrawl();
		$this->expectException(\InvalidArgumentException::class);
		$immediate_task->schedule();
	}

	public function test_run_task_no_exception(): void {
		Functions\expect( 'wp_remote_get' )->andReturn( [ 'response' => [ 'code' => 200 ] ] );
		Functions\expect( 'wp_remote_retrieve_response_code' )->andReturn( 200 );
		Functions\expect( 'wp_remote_retrieve_body' )->andReturn( '<html></html>' );
		Functions\expect( 'delete_option' );
		Functions\expect( 'update_option' );
		Functions\expect( 'home_url' )->once()->andReturn('http://localhost');
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		$immediate_task = new ImmediateCrawl();
		$immediate_task->run_task('http://localhost');
		$this->assertTrue(true);
	}
}
