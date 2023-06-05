<?php

namespace WpSeoCrawler\Tests\Unit;

use DevWael\WpSeoCrawler\WpSeoCrawler;

class WpSeoCrawlerTest extends AbstractUnitTestCase {
	public function test_wp_seo_crawler_instance() {
		$this->assertInstanceOf( WpSeoCrawler::class, WpSeoCrawler::instance() );
	}
}
