<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Components\Campaigns\Domain;

use Fundrik\Core\Components\Campaigns\Domain\CampaignTarget;
use Fundrik\Core\Components\Campaigns\Domain\Exceptions\InvalidCampaignTargetException;
use Fundrik\Core\Tests\FundrikTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass( CampaignTarget::class )]
final class CampaignTargetTest extends FundrikTestCase {

	#[Test]
	public function creates_when_enabled_target_with_amount(): void {

		$target = CampaignTarget::create( true, 100 );

		$this->assertTrue( $target->is_enabled );
		$this->assertEquals( 100, $target->amount );
	}

	#[Test]
	public function throws_when_target_enabled_but_zero_amount(): void {

		$this->expectException( InvalidCampaignTargetException::class );
		$this->expectExceptionMessage( 'Target amount must be positive when targeting is enabled, given 0' );

		CampaignTarget::create( true, 0 );
	}

	#[Test]
	public function throws_when_target_enabled_but_negative_amount(): void {

		$this->expectException( InvalidCampaignTargetException::class );
		$this->expectExceptionMessage( 'Target amount must be positive when targeting is enabled, given -500' );

		CampaignTarget::create( true, -500 );
	}

	#[Test]
	public function creates_when_disabled_target_with_zero_amount(): void {

		$target = CampaignTarget::create( false, 0 );

		$this->assertFalse( $target->is_enabled );
		$this->assertEquals( 0, $target->amount );
	}

	#[Test]
	public function throws_when_target_disabled_but_positive_amount(): void {

		$this->expectException( InvalidCampaignTargetException::class );
		$this->expectExceptionMessage( 'Target amount should be zero when targeting is disabled, given 100' );

		CampaignTarget::create( false, 100 );
	}

	#[Test]
	public function throws_when_target_disabled_but_negative_amount(): void {

		$this->expectException( InvalidCampaignTargetException::class );
		$this->expectExceptionMessage( 'Target amount should be zero when targeting is disabled, given -500' );

		CampaignTarget::create( false, -500 );
	}
}
