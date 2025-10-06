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
	public function creates_enabled_target_with_positive_amount(): void {

		$target = CampaignTarget::create( true, 100 );

		$this->assertTrue( $target->is_enabled() );
		$this->assertSame( 100, $target->get_amount() );
	}

	#[Test]
	public function throws_when_enabled_but_zero_amount(): void {

		$this->expectException( InvalidCampaignTargetException::class );
		$this->expectExceptionMessage( 'Target amount must be positive when targeting is enabled. Given: 0.' );

		CampaignTarget::create( true, 0 );
	}

	#[Test]
	public function throws_when_enabled_but_negative_amount(): void {

		$this->expectException( InvalidCampaignTargetException::class );
		$this->expectExceptionMessage( 'Target amount must be positive when targeting is enabled. Given: -500.' );

		CampaignTarget::create( true, -500 );
	}

	#[Test]
	public function creates_disabled_target_with_zero_amount(): void {

		$target = CampaignTarget::create( false, 0 );

		$this->assertFalse( $target->is_enabled() );
		$this->assertSame( 0, $target->get_amount() );
	}

	#[Test]
	public function throws_when_disabled_but_positive_amount(): void {

		$this->expectException( InvalidCampaignTargetException::class );
		$this->expectExceptionMessage( 'Target amount must be zero when targeting is disabled. Given: 100.' );

		CampaignTarget::create( false, 100 );
	}

	#[Test]
	public function throws_when_disabled_but_negative_amount(): void {

		$this->expectException( InvalidCampaignTargetException::class );
		$this->expectExceptionMessage( 'Target amount must be zero when targeting is disabled. Given: -500.' );

		CampaignTarget::create( false, -500 );
	}

	#[Test]
	public function equals_returns_true_for_identical_targets(): void {

		$t1 = CampaignTarget::create( true, 100 );
		$t2 = CampaignTarget::create( true, 100 );

		$this->assertTrue( $t1->equals( $t2 ) );
	}

	#[Test]
	public function equals_returns_false_for_different_targets(): void {

		$t1 = CampaignTarget::create( true, 100 );
		$t2 = CampaignTarget::create( true, 200 );

		$this->assertFalse( $t1->equals( $t2 ) );
	}
}
