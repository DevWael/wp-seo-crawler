<?php

namespace WpSeoCrawler\Tests\Unit\FrontEnd;

use DevWael\WpSeoCrawler\FrontEnd\Routes\RewriteRule;
use DevWael\WpSeoCrawler\FrontEnd\Routes\Templates\SitemapTemplate;
use WpSeoCrawler\Tests\Unit\AbstractUnitTestCase;
use Brain\Monkey\Functions;

class RewriteRuleTest extends AbstractUnitTestCase {
	/**
	 * Test if the register query var returns an array with the correct value
	 *
	 * @return void
	 */
	public function test_register_query_var_adds_expected_var(): void {
		$vars   = [ 'var1', 'var2' ];
		$view   = new RewriteRule();
		$result = $view->register_query_var( $vars );
		$this->assertIsArray( $result );
		$this->assertContains( 'wpseoc_template', $result );
	}

	/**
	 * Test load template returns the correct value
	 *
	 * @return void
	 */
	public function test_load_template_return_string(): void {
		global $wp_query;
		$wp_query          = new \stdClass();
		$wp_query->is_home = true;

		Functions\expect( 'get_query_var' )
			->once()
			->with( 'wpseoc_template' )
			->andReturn( 'sitemap-html' );

		$template = \Mockery::mock( SitemapTemplate::class );
		$template->shouldReceive( 'template_path' )->andReturn( '/wp-seo-crawler/sitemap-html.php' );

		$rulePath = new RewriteRule( $template );
		$result   = $rulePath->load_template( '' );
		$this->assertStringEndsWith( '/wp-seo-crawler/sitemap-html.php', $result );
	}

	/**
	 * Test load template returns the same as parameter if the query var
	 * is not equals sitemap-html
	 *
	 * @return void
	 */
	public function test_load_template_returns_string_without_query_var(): void {
		Functions\expect( 'get_query_var' );
		$view = new RewriteRule();
		$this->assertStringContainsString( 'test', $view->load_template( 'test' ) );
	}

	/**
	 * Test if the Register method contains required custom actions
	 *
	 * @return void
	 */
	public function test_register_contains_action(): void {
		Functions\expect( 'add_rewrite_rule' );
		$template = \Mockery::mock( SitemapTemplate::class );
		$template->shouldReceive( 'template_regex' )->andReturn( '^sitemap.html$' );
		$template->shouldReceive( 'template_query' )->andReturn( 'index.php?wpseoc_template=sitemap-html' );
		$template->shouldReceive( 'template_path' )->andReturn( 'wp-seo-crawler/sitemap-html.php' );

		$rewriteRule = new RewriteRule( $template );


		$rewriteRule->register();
		$this->assertSame( 1, did_action( 'wpseoc_rewrite_rule_added' ) );
	}

	/**
	 * Test if the template title adds the title key to the title parts array
	 *
	 * @return void
	 */
	public function test_title_parts_has_title_key(): void {
		Functions\stubTranslationFunctions();
		Functions\expect( 'get_query_var' )
			->with( 'wpseoc_template' )
			->andReturn( 'sitemap-html' );
		$titleParts  = [
			'link'  => '',
			'query' => '',
		];
		$rewriteRule = new RewriteRule();
		$result      = $rewriteRule->template_title( $titleParts );
		$this->assertArrayHasKey( 'title', $result );
	}
}
