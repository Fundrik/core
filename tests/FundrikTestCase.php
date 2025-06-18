<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class FundrikTestCase extends PHPUnitTestCase {

	use MockeryPHPUnitIntegration;

	protected function setUp(): void {

		parent::setUp();

		Monkey\setUp();
	}

	protected function tearDown(): void {

		Monkey\tearDown();

		parent::tearDown();
	}
}
