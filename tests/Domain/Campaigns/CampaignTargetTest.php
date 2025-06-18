<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Domain\Campaigns;

use Fundrik\Core\Domain\Campaigns\CampaignTarget;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass( CampaignTarget::class )]
class CampaignTargetTest extends TestCase {

	#[Test]
	public function creates_when_enabled_target_with_amount(): void {

		$target = CampaignTarget::create( true, 1000 );

		$this->assertEquals( 1000, $target->amount );
	}

	#[Test]
	public function throws_when_target_enabled_but_zero_amount(): void {

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Target amount must be positive when targeting is enabled, given 0' );

		CampaignTarget::create( true, 0 );
	}

	#[Test]
	public function throws_when_target_enabled_but_negative_amount(): void {

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Target amount must be positive when targeting is enabled, given -500' );

		CampaignTarget::create( true, -500 );
	}

	#[Test]
	public function creates_when_disabled_target_with_zero_amount(): void {

		$target = CampaignTarget::create( false, 0 );

		$this->assertEquals( 0, $target->amount );
	}

	#[Test]
	public function throws_when_target_disabled_but_positive_amount(): void {

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Target amount should be zero when targeting is disabled, given 100' );

		CampaignTarget::create( false, 100 );
	}

	#[Test]
	public function throws_when_target_disabled_but_negative_amount(): void {

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Target amount should be zero when targeting is disabled, given -500' );

		CampaignTarget::create( false, -500 );
	}
}
