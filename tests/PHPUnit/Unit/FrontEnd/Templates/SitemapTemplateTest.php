<?php

namespace WpSeoCrawler\Tests\Unit\FrontEnd\Templates;

use DevWael\WpSeoCrawler\FrontEnd\Routes\Templates\SitemapTemplate;
use WpSeoCrawler\Tests\Unit\AbstractUnitTestCase;
use Brain\Monkey\Functions;

class SitemapTemplateTest extends AbstractUnitTestCase {

	public function test_template_path_returns_exact_string(): void {
		Functions\expect( 'locate_template' )->andReturn( 'theme/dir' );
		$template = new SitemapTemplate();
		$this->assertEquals( 'theme/dir', $template->template_path() );
	}

	public function test_template_path_returns_plugin_path(): void {
		Functions\expect( 'locate_template' )->andReturn( false );
		$template = new SitemapTemplate();
		$this->assertStringEndsWith( 'templates/sitemap-html.php', $template->template_path() );
	}

	public function test_template_regex(): void {
		$template = new SitemapTemplate();
		$this->assertEquals( '^sitemap.html$', $template->template_regex() );
	}

	public function test_template_query(): void {
		$template = new SitemapTemplate();
		$this->assertEquals( 'index.php?wpseoc_template=sitemap-html', $template->template_query() );
	}
}
