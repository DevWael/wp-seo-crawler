<?php

namespace WpSeoCrawler\Tests\Unit\Admin;

use DevWael\WpSeoCrawler\Admin\Request;
use WpSeoCrawler\Tests\Unit\AbstractUnitTestCase;

class RequestTest extends AbstractUnitTestCase {
	public function test_get_returns_exact_array(): void {
		$request = new Request();

		$_GET = [ 'param1' => 'value1', 'param2' => 'value2' ];
		$this->assertEquals( $_GET, $request->get() );
	}

	public function test_post_returns_exact_array(): void {
		$request = new Request();

		$_POST = [ 'param1' => 'value1', 'param2' => 'value2' ];
		$this->assertEquals( $_POST, $request->post() );
	}
}
