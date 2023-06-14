<?php

namespace WpSeoCrawler\Tests\Unit;

use DevWael\WpSeoCrawler\WpSeoCrawler;
use Brain\Monkey\Functions;

class WpSeoCrawlerTest extends AbstractUnitTestCase {
	public function test_wp_seo_crawler_instance() {
		Functions\expect( 'esc_url' )->andReturnFirstArg();
		Functions\expect( 'home_url' );
		Functions\expect( 'is_admin' )->andReturn( true );

		$this->assertInstanceOf( WpSeoCrawler::class, WpSeoCrawler::instance() );
	}
}
