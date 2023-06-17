<?php

namespace WpSeoCrawler\Tests\Unit\FrontEnd\Templates;

use DevWael\WpSeoCrawler\FrontEnd\Routes\Templates\Components;
use WpSeoCrawler\Tests\Unit\AbstractUnitTestCase;
use Brain\Monkey\Functions;

class ComponentsTest extends AbstractUnitTestCase {
	public function test_header_html(): void {
		Functions\stubs(
			[
				'language_attributes',
				'bloginfo',
				'wp_head',
				'body_class',
				'wp_body_open',
			],
			true
		);
		ob_start();
		Components::header();
		$actual = ob_get_clean();
		$this->assertStringContainsString( '<!DOCTYPE html>', $actual );
	}

	public function test_footer_html(): void {
		Functions\expect( 'wp_footer' );
		ob_start();
		Components::footer();
		$actual = ob_get_clean();
		$this->assertStringContainsString( '</html>', $actual );
	}
}
