<?php

namespace WpSeoCrawler\Tests\Unit\BackgroundWorkers;

use DevWael\WpSeoCrawler\BackgroundWorkers\HourlyCrawl;
use WpSeoCrawler\Tests\Unit\AbstractUnitTestCase;
use Brain\Monkey\Functions;


class HourlyCrawlTest extends AbstractUnitTestCase {

	public function test_no_url_throw_exception(): void {
		Functions\expect( 'home_url' )->once();
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
		$immediate_task = new HourlyCrawl();
		$this->expectException(\InvalidArgumentException::class);
		$immediate_task->schedule();
	}
}
