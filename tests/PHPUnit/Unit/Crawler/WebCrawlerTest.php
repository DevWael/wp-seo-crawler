<?php

namespace WpSeoCrawler\Tests\Unit\Crawler;

use DevWael\WpSeoCrawler\Crawler\WebCrawler;
use Symfony\Component\DomCrawler\Crawler;
use WpSeoCrawler\Tests\Unit\AbstractUnitTestCase;
use Brain\Monkey\Functions;
use Brain\Monkey\Filters;

class WebCrawlerTest extends AbstractUnitTestCase {

	private function working_stubs() {
		Functions\expect( 'wp_remote_get' )->andReturn( [
			'body'     => '<html><body></body></html>',
			'response' => [
				'code' => 200,
			],
		] );
		Functions\expect( 'wp_remote_retrieve_response_code' )->andReturn( 200 );
		Functions\stubEscapeFunctions();
		Functions\stubs(
			[
				'home_url'            => 'https://example.com',
				'admin_url'           => 'https://example.com/wp-admin',
				'wp_login_url'        => 'https://example.com/wp-login.php',
				'wp_logout_url'       => 'https://example.com/?action=logout',
				'wp_lostpassword_url' => 'https://example.com/wp-login.php?action=lostpassword',
				'wp_registration_url' => 'https://example.com/wp-login.php?action=register',
			]
		);
	}

	public function testCrawlReturnsArrayOfLinks() {
		$this->working_stubs();
		$html = '<html>
            	<head>
					<title>Test</title>
				</head>
                <body>
                    <a href="https://example.com/page1">Page 1</a>
                    <a href="https://example.com/page2">Page 2</a>
                </body>
            </html>';
		Functions\expect( 'wp_remote_retrieve_body' )->andReturn( $html );

		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com' );

		$expectedLinks = [
			[
				'href'   => 'https://example.com/page1',
				'text'   => 'Page 1',
				'title'  => '',
				'_blank' => false,
			],
			[
				'href'   => 'https://example.com/page2',
				'text'   => 'Page 2',
				'title'  => '',
				'_blank' => false,
			],
		];

		$this->assertEquals( $expectedLinks, $crawler->crawl() );
	}

	public function testCrawlExcludesHomeUrl() {
		$this->working_stubs();
		$html = '<html>
            	<head>
					<title>Test</title>
				</head>
                <body>
                    <a href="https://example.com">Page 1</a>
                    <a href="https://example.com/page2">Page 2</a>
                </body>
            </html>';
		Functions\expect( 'wp_remote_retrieve_body' )->andReturn( $html );

		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com' );

		$expectedLinks = [
			[
				'href'   => 'https://example.com/page2',
				'text'   => 'Page 2',
				'title'  => '',
				'_blank' => false,
			],
		];

		$this->assertEquals( $expectedLinks, $crawler->crawl() );
	}

	public function testCrawlExcludesAdminUrl() {
		$this->working_stubs();
		$html = '<html>
				<head>
					<title>Test</title>
				</head>
				<body>
					<a href="https://example.com/wp-admin">Page 1</a>
					<a href="https://example.com/page2">Page 2</a>
				</body>
			</html>';
		Functions\expect( 'wp_remote_retrieve_body' )->andReturn( $html );

		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com' );

		$expectedLinks = [
			[
				'href'   => 'https://example.com/page2',
				'text'   => 'Page 2',
				'title'  => '',
				'_blank' => false,
			],
		];

		$this->assertEquals( $expectedLinks, $crawler->crawl() );
	}

	public function testCrawlExcludesLoginUrl() {
		$this->working_stubs();
		$html = '<html>
				<head>
					<title>Test</title>
				</head>
				<body>
					<a href="https://example.com/wp-login.php">Page 1</a>
					<a href="https://example.com/page2">Page 2</a>
				</body>
			</html>';
		Functions\expect( 'wp_remote_retrieve_body' )->andReturn( $html );

		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com' );

		$expectedLinks = [
			[
				'href'   => 'https://example.com/page2',
				'text'   => 'Page 2',
				'title'  => '',
				'_blank' => false,
			],
		];

		$this->assertEquals( $expectedLinks, $crawler->crawl() );
	}

	public function testCrawlExcludesLogoutUrl() {
		$this->working_stubs();
		$html = '<html>
				<head>
					<title>Test</title>
				</head>
				<body>
					<a href="https://example.com/?action=logout">Page 1</a>
					<a href="https://example.com/page2">Page 2</a>
				</body>
			</html>';
		Functions\expect( 'wp_remote_retrieve_body' )->andReturn( $html );

		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com' );

		$expectedLinks = [
			[
				'href'   => 'https://example.com/page2',
				'text'   => 'Page 2',
				'title'  => '',
				'_blank' => false,
			],
		];
		$this->assertEquals( $expectedLinks, $crawler->crawl() );
	}

	public function testCrawlExcludesRegistrationUrl() {
		$this->working_stubs();
		$html = '<html>
				<head>
					<title>Test</title>
				</head>
				<body>
					<a href="https://example.com/wp-login.php?action=register">Page 1</a>
					<a href="https://example.com/page2">Page 2</a>
				</body>
			</html>';
		Functions\expect( 'wp_remote_retrieve_body' )->andReturn( $html );

		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com' );

		$expectedLinks = [
			[
				'href'   => 'https://example.com/page2',
				'text'   => 'Page 2',
				'title'  => '',
				'_blank' => false,
			],
		];

		$this->assertEquals( $expectedLinks, $crawler->crawl() );
	}

	public function testCrawlExcludesLostPassword() {
		$this->working_stubs();
		$html = '<html>
				<head>
					<title>Test</title>
				</head>
				<body>
					<a href="https://example.com/wp-login.php?action=lostpassword">Page 1</a>
					<a href="https://example.com/page2">Page 2</a>
				</body>
			</html>';
		Functions\expect( 'wp_remote_retrieve_body' )->andReturn( $html );

		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com' );

		$expectedLinks = [
			[
				'href'   => 'https://example.com/page2',
				'text'   => 'Page 2',
				'title'  => '',
				'_blank' => false,
			],
		];

		$this->assertEquals( $expectedLinks, $crawler->crawl() );
	}

	public function testLinkNotHasHref() {
		$this->working_stubs();
		$html = '<html>
				<head>
					<title>Test</title>
				</head>
				<body>
					<a>Page 1</a>
					<a href="https://example.com/page2">Page 2</a>
				</body>
			</html>';
		Functions\expect( 'wp_remote_retrieve_body' )->andReturn( $html );

		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com' );

		$expectedLinks = [
			[
				'href'   => 'https://example.com/page2',
				'text'   => 'Page 2',
				'title'  => '',
				'_blank' => false,
			],
		];

		$this->assertEquals( $expectedLinks, $crawler->crawl() );
	}

	public function testLinkEmptyHref() {
		$this->working_stubs();
		$html = '<html>
				<head>
					<title>Test</title>
				</head>
				<body>
					<a href="">Page 1</a>
					<a href="https://example.com/page2">Page 2</a>
				</body>
			</html>';
		Functions\expect( 'wp_remote_retrieve_body' )->andReturn( $html );

		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com' );

		$expectedLinks = [
			[
				'href'   => 'https://example.com/page2',
				'text'   => 'Page 2',
				'title'  => '',
				'_blank' => false,
			],
		];

		$this->assertEquals( $expectedLinks, $crawler->crawl() );
	}

	public function testLinkStartWithHash() {
		$this->working_stubs();
		$html = '<html>
				<head>
					<title>Test</title>
				</head>
				<body>
					<a href="#test">Page 1</a>
					<a href="https://example.com/page2">Page 2</a>
				</body>
			</html>';
		Functions\expect( 'wp_remote_retrieve_body' )->andReturn( $html );

		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com' );

		$expectedLinks = [
			[
				'href'   => 'https://example.com/page2',
				'text'   => 'Page 2',
				'title'  => '',
				'_blank' => false,
			],
		];

		$this->assertEquals( $expectedLinks, $crawler->crawl() );
	}

	public function testLinkStartWithJavascript() {
		$this->working_stubs();
		$html = '<html>
				<head>
					<title>Test</title>
				</head>
				<body>
					<a href="javascript:void(0)">Page 1</a>
					<a href="https://example.com/page2">Page 2</a>
				</body>
			</html>';
		Functions\expect( 'wp_remote_retrieve_body' )->andReturn( $html );

		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com' );

		$expectedLinks = [
			[
				'href'   => 'https://example.com/page2',
				'text'   => 'Page 2',
				'title'  => '',
				'_blank' => false,
			],
		];

		$this->assertEquals( $expectedLinks, $crawler->crawl() );
	}

	public function testLinkForCurrentPage() {
		$this->working_stubs();
		$html = '<html>
				<head>
					<title>Test</title>
				</head>
				<body>
					<a href="https://example.com/page1">Page 1</a>
					<a href="https://example.com/page2">Page 2</a>
				</body>
			</html>';
		Functions\expect( 'wp_remote_retrieve_body' )->andReturn( $html );

		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com/page1' );

		$expectedLinks = [
			[
				'href'   => 'https://example.com/page2',
				'text'   => 'Page 2',
				'title'  => '',
				'_blank' => false,
			],
		];

		$this->assertEquals( $expectedLinks, $crawler->crawl() );
	}

	public function testFilterReturnTrue() {
		$this->working_stubs();
		$html = '<html>
				<head>
					<title>Test</title>
				</head>
				<body>
					<a href="https://example.com/page1">Page 1</a>
					<a href="https://example.com/page2">Page 2</a>
				</body>
			</html>';
		Functions\expect( 'wp_remote_retrieve_body' )->andReturn( $html );

		Filters\expectApplied( 'wpseoc_skip_link' )->andReturn( true );

		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com/page1' );

		$expectedLinks = [];

		$this->assertEquals( $expectedLinks, $crawler->crawl() );
	}

	public function testCrawlThrowsRuntimeExceptionIfResponseNotOk() {
		Functions\expect( 'wp_remote_get' )->andReturn( [
			'body'     => '<html><body></body></html>',
			'response' => [
				'code' => 200,
			],
		] );
		Functions\expect( 'wp_remote_retrieve_response_code' )->andReturn( 500 );
		Functions\stubTranslationFunctions();
		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com/nonexistent' );

		$this->expectException( \RuntimeException::class );
		$this->expectExceptionMessage( 'Failed to fetch that URL' );

		$crawler->crawl();
	}

	public function testCrawlThrowsRuntimeExceptionIfRequestFailed(){
		$error = new class {
			// phpcs:disable
			public function get_error_message(): string
			{
				return 'error';
			}
			// phpcs:enable
		};
		Functions\expect( 'wp_remote_get' )->andReturn( $error );
		Functions\expect( 'is_wp_error' )->andReturn( true );
		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com/nonexistent' );
		$this->expectException(\RuntimeException::class);
		$crawler->crawl();
	}

	public function testCrawlThrowsRuntimeExceptionIfEmptyHtml() {
		Functions\expect( 'wp_remote_get' )->andReturn( [
			'body'     => '',
			'response' => [
				'code' => 200,
			],
		] );
		Functions\expect( 'wp_remote_retrieve_response_code' )->andReturn( 200 );
		Functions\expect( 'wp_remote_retrieve_body' )->andReturn( '' );
		Functions\stubTranslationFunctions();
		$crawler = new WebCrawler( new Crawler() );
		$crawler->set_url( 'https://example.com/nonexistent' );

		$this->expectException( \RuntimeException::class );
		$this->expectExceptionMessage( 'The page is not loaded.' );

		$crawler->crawl();
	}

	public function testCrawlThrowsRuntimeExceptionIfUrlNotSet(): void {
		Functions\stubTranslationFunctions();
		$crawler = new WebCrawler( new Crawler() );

		$this->expectException( \RuntimeException::class );
		$this->expectExceptionMessage( 'The url is not set' );

		$crawler->crawl();
	}
}
