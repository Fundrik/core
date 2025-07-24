<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Components\Campaigns\Domain;

use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTarget;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTitle;
use Fundrik\Core\Components\Shared\Domain\EntityId;
use Fundrik\Core\Support\TypeCaster;
use Fundrik\Core\Tests\FundrikTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass( Campaign::class )]
#[UsesClass( CampaignTarget::class )]
#[UsesClass( CampaignTitle::class )]
#[UsesClass( EntityId::class )]
#[UsesClass( TypeCaster::class )]
final class CampaignTest extends FundrikTestCase {

	#[Test]
	public function campaign_returns_all_expected_values(): void {

		$campaign = $this->make_campaign();

		$this->assertEquals( 1, $campaign->get_id() );
		$this->assertEquals( 'Test Campaign', $campaign->get_title() );
		$this->assertTrue( $campaign->is_active() );
		$this->assertTrue( $campaign->is_open() );
		$this->assertTrue( $campaign->has_target() );
		$this->assertEquals( 100, $campaign->get_target_amount() );
	}

	#[Test]
	public function campaign_allows_uuid_as_id(): void {

		$uuid = '7f2c8a19-8b3a-42e0-8573-5e672c7e4f01';

		$campaign = $this->make_campaign( id: $uuid );

		$this->assertSame( $uuid, $campaign->get_id() );
	}

	#[Test]
	public function campaign_without_enabled_target(): void {

		$campaign = $this->make_campaign( has_target: false, target_amount: 0 );

		$this->assertFalse( $campaign->has_target() );
		$this->assertEquals( 0, $campaign->get_target_amount() );
		$this->assertEquals( 1, $campaign->get_id() );
	}
}
