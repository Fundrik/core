<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Domain\Campaigns;

use Fundrik\Core\Application\Campaigns\CampaignDto;
use Fundrik\Core\Domain\Campaigns\Campaign;
use Fundrik\Core\Domain\Campaigns\CampaignFactory;
use Fundrik\Core\Domain\Campaigns\CampaignTarget;
use Fundrik\Core\Domain\Campaigns\CampaignTitle;
use Fundrik\Core\Domain\Campaigns\Exceptions\InvalidCampaignTargetException;
use Fundrik\Core\Domain\EntityId;
use Fundrik\Core\Domain\Exceptions\InvalidEntityIdException;
use Fundrik\Core\Support\TypeCaster;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( CampaignFactory::class )]
#[UsesClass( Campaign::class )]
#[UsesClass( EntityId::class )]
#[UsesClass( CampaignTitle::class )]
#[UsesClass( CampaignTarget::class )]
#[UsesClass( TypeCaster::class )]
class CampaignFactoryTest extends TestCase {

	private CampaignFactory $factory;

	protected function setUp(): void {

		$this->factory = new CampaignFactory();
	}

	#[Test]
	public function creates_campaign_with_int_id(): void {

		$id = 1;

		$campaign = $this->factory->create(
			new CampaignDto(
				id: $id,
				title: 'Test Campaign',
				is_enabled: true,
				is_open: false,
				has_target: true,
				target_amount: 1000,
			)
		);

		$this->assertInstanceOf( Campaign::class, $campaign );

		$this->assertEquals( $id, $campaign->get_id() );
		$this->assertEquals( 'Test Campaign', $campaign->get_title() );
		$this->assertEquals( true, $campaign->is_enabled() );
		$this->assertEquals( false, $campaign->is_open() );
		$this->assertEquals( true, $campaign->has_target() );
		$this->assertEquals( 1000, $campaign->get_target_amount() );
	}

	#[Test]
	public function creates_campaign_with_uuid_id(): void {

		$uuid = '0196934d-e117-71aa-ab63-cff172292bd2';

		$campaign = $this->factory->create(
			new CampaignDto(
				id: $uuid,
				title: 'UUID Campaign',
				is_enabled: true,
				is_open: true,
				has_target: false,
				target_amount: 0,
			)
		);

		$this->assertInstanceOf( Campaign::class, $campaign );

		$this->assertEquals( $uuid, $campaign->get_id() );
	}

	#[Test]
	public function throws_when_campaign_target_is_invalid(): void {

		$this->expectException( InvalidCampaignTargetException::class );
		$this->expectExceptionMessage( 'Target amount must be positive when targeting is enabled, given 0' );

		$this->factory->create(
			new CampaignDto(
				id: 1,
				title: 'Invalid Campaign',
				is_enabled: true,
				is_open: true,
				has_target: true,
				target_amount: 0,
			)
		);
	}

	#[Test]
	public function throws_when_entity_id_is_invalid(): void {

		$this->expectException( InvalidEntityIdException::class );
		$this->expectExceptionMessage( 'EntityId must be a positive, given: -1' );

		$this->factory->create(
			new CampaignDto(
				id: -1,
				title: 'Invalid Campaign',
				is_enabled: true,
				is_open: true,
				has_target: false,
				target_amount: 0,
			)
		);
	}
}
