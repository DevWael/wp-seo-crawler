<?php

namespace WpSeoCrawler\Tests\Unit\Storage;

use DevWael\WpSeoCrawler\Storage\DataController;
use WpSeoCrawler\Tests\Unit\AbstractUnitTestCase;
use Brain\Monkey\Functions;

class DataControllerTest extends AbstractUnitTestCase {
	public function test_read_empty_value(): void {
		Functions\expect( 'home_url' )->once();
		Functions\expect( 'get_option' )->once()->andReturn( [ 'data' ] );
		Functions\stubEscapeFunctions();
		$controller = new DataController();
		$this->assertIsArray( $controller->read() );
	}

	public function test_save_data_return_array(): void {
		Functions\expect( 'home_url' )->once();
		Functions\expect( 'update_option' )->once()->andReturn( [ 'data' ] );
		Functions\stubEscapeFunctions();
		$controller = new DataController();
		$this->assertIsArray( $controller->save( [
			[
				'found_at' => 'https://example.com',
				'href'     => 'https://example.com',
				'text'     => 'Example',
				'title'    => 'Example',
				'_blank'   => false,
			],
		] ) );
	}
}
