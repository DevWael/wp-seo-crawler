<?php

namespace WpSeoCrawler\Tests\Unit;

use Brain\Monkey;
use PHPUnit\Framework\TestCase;
abstract class AbstractUnitTestCase extends TestCase{
	/**
	 * Sets up the environment.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	/**
	 * Tears down the environment.
	 *
	 * @return void
	 */
	protected function tearDown(): void
	{
		Monkey\tearDown();
		parent::tearDown();
	}
}
