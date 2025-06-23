<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Application\Campaigns;

use Fundrik\Core\Application\Campaigns\CampaignDto;
use Fundrik\Core\Application\Campaigns\CampaignService;
use Fundrik\Core\Application\Campaigns\Interfaces\CampaignRepositoryInterface;
use Fundrik\Core\Domain\Campaigns\Campaign;
use Fundrik\Core\Domain\Campaigns\CampaignFactory;
use Fundrik\Core\Domain\Campaigns\CampaignTarget;
use Fundrik\Core\Domain\Campaigns\CampaignTitle;
use Fundrik\Core\Domain\EntityId;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( CampaignService::class )]
#[UsesClass( Campaign::class )]
#[UsesClass( EntityId::class )]
#[UsesClass( CampaignFactory::class )]
#[UsesClass( CampaignTitle::class )]
#[UsesClass( CampaignTarget::class )]
final class CampaignServiceTest extends TestCase {

	private CampaignRepositoryInterface&MockInterface $repository;

	private CampaignService $service;

	protected function setUp(): void {

		parent::setUp();

		$this->repository = Mockery::mock( CampaignRepositoryInterface::class );

		$this->service = new CampaignService(
			new CampaignFactory(),
			$this->repository,
		);
	}

	#[Test]
	public function get_by_id_returns_campaign(): void {

		$campaign_id = EntityId::create( 123 );

		$dto = new CampaignDto(
			id: 123,
			title: 'Array Campaign',
			is_enabled: true,
			is_open: true,
			has_target: true,
			target_amount: 1_500,
		);

		$this->repository
			->shouldReceive( 'get_by_id' )
			->once()
			->with( $this->identicalTo( $campaign_id ) )
			->andReturn( $dto );

		$result = $this->service->get_campaign_by_id( $campaign_id );

		$this->assertInstanceOf( Campaign::class, $result );
	}

	#[Test]
	public function get_by_id_returns_null_when_not_found(): void {

		$campaign_id = EntityId::create( 999 );

		$this->repository
			->shouldReceive( 'get_by_id' )
			->once()
			->with( $this->identicalTo( $campaign_id ) )
			->andReturn( null );

		$result = $this->service->get_campaign_by_id( $campaign_id );

		$this->assertNull( $result );
	}

	#[Test]
	public function get_all_campaigns_returns_list_of_campaigns(): void {

		$dto1 = new CampaignDto(
			id: 123,
			title: 'Campaign One',
			is_enabled: true,
			is_open: true,
			has_target: true,
			target_amount: 1_500,
		);

		$dto2 = new CampaignDto(
			id: 124,
			title: 'Campaign Two',
			is_enabled: true,
			is_open: true,
			has_target: true,
			target_amount: 1_500,
		);

		$this->repository
			->shouldReceive( 'get_all' )
			->once()
			->andReturn( [ $dto1, $dto2 ] );

		$result = $this->service->get_all_campaigns();

		$this->assertCount( 2, $result );
		$this->assertInstanceOf( Campaign::class, $result[0] );
		$this->assertInstanceOf( Campaign::class, $result[1] );
	}

	#[Test]
	public function save_campaign_inserts_when_not_exists(): void {

		$dto = new CampaignDto(
			id: 555,
			title: 'New Campaign',
			is_enabled: true,
			is_open: true,
			has_target: false,
			target_amount: 0,
		);

		$this->repository
			->shouldReceive( 'exists' )
			->once()
			->with( Mockery::type( Campaign::class ) )
			->andReturn( false );

		$this->repository
			->shouldReceive( 'insert' )
			->once()
			->with( Mockery::type( Campaign::class ) )
			->andReturn( true );

		$result = $this->service->save_campaign( $dto );

		$this->assertTrue( $result );
	}

	#[Test]
	public function save_campaign_updates_when_exists(): void {

		$dto = new CampaignDto(
			id: 777,
			title: 'Existing Campaign',
			is_enabled: true,
			is_open: false,
			has_target: true,
			target_amount: 999,
		);

		$this->repository
			->shouldReceive( 'exists' )
			->once()
			->with( Mockery::type( Campaign::class ) )
			->andReturn( true );

		$this->repository
			->shouldReceive( 'update' )
			->once()
			->with( Mockery::type( Campaign::class ) )
			->andReturn( true );

		$result = $this->service->save_campaign( $dto );

		$this->assertTrue( $result );
	}

	#[Test]
	public function delete_campaign_returns_true_when_successful(): void {

		$campaign_id = EntityId::create( 42 );

		$this->repository
			->shouldReceive( 'delete' )
			->once()
			->with( $this->identicalTo( $campaign_id ) )
			->andReturn( true );

		$result = $this->service->delete_campaign( $campaign_id );

		$this->assertTrue( $result );
	}

	#[Test]
	public function delete_campaign_returns_false_when_failed(): void {

		$campaign_id = EntityId::create( 999 );

		$this->repository
			->shouldReceive( 'delete' )
			->once()
			->with( $this->identicalTo( $campaign_id ) )
			->andReturn( false );

		$result = $this->service->delete_campaign( $campaign_id );

		$this->assertFalse( $result );
	}
}
