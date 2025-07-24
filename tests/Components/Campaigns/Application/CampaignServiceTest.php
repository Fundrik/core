<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Components\Campaigns\Application;

use Fundrik\Core\Components\Campaigns\Application\CampaignAssembler;
use Fundrik\Core\Components\Campaigns\Application\CampaignDto;
use Fundrik\Core\Components\Campaigns\Application\CampaignService;
use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositoryPortInterface;
use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTarget;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTitle;
use Fundrik\Core\Components\Shared\Domain\EntityId;
use Fundrik\Core\Support\TypeCaster;
use Fundrik\Core\Tests\FundrikTestCase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass( CampaignService::class )]
#[UsesClass( CampaignAssembler::class )]
#[UsesClass( CampaignDto::class )]
#[UsesClass( Campaign::class )]
#[UsesClass( CampaignTarget::class )]
#[UsesClass( CampaignTitle::class )]
#[UsesClass( EntityId::class )]
#[UsesClass( TypeCaster::class )]
final class CampaignServiceTest extends FundrikTestCase {

	private CampaignRepositoryPortInterface&MockInterface $repository;

	private CampaignService $service;

	protected function setUp(): void {

		parent::setUp();

		$this->repository = Mockery::mock( CampaignRepositoryPortInterface::class );

		$this->service = new CampaignService(
			new CampaignAssembler(),
			$this->repository,
		);
	}

	#[Test]
	public function find_campaign_by_id_returns_campaign(): void {

		$campaign_id = EntityId::create( 1 );

		$this->repository
			->shouldReceive( 'get_by_id' )
			->once()
			->with( $this->identicalTo( $campaign_id ) )
			->andReturn( $this->make_campaign_dto() );

		$result = $this->service->find_campaign_by_id( $campaign_id );

		$this->assertInstanceOf( Campaign::class, $result );
	}

	#[Test]
	public function find_campaign_by_id_returns_null_when_not_found(): void {

		$campaign_id = EntityId::create( 999 );

		$this->repository
			->shouldReceive( 'get_by_id' )
			->once()
			->with( $this->identicalTo( $campaign_id ) )
			->andReturn( null );

		$result = $this->service->find_campaign_by_id( $campaign_id );

		$this->assertNull( $result );
	}

	#[Test]
	public function find_all_campaigns_campaigns_returns_list_of_campaigns(): void {

		$dto1 = $this->make_campaign_dto();
		$dto2 = $this->make_campaign_dto( id: 2 );

		$this->repository
			->shouldReceive( 'get_all' )
			->once()
			->andReturn( [ $dto1, $dto2 ] );

		$result = $this->service->find_all_campaigns();

		$this->assertCount( 2, $result );
		$this->assertInstanceOf( Campaign::class, $result[0] );
		$this->assertInstanceOf( Campaign::class, $result[1] );
	}

	#[Test]
	public function find_all_campaigns_returns_empty_array_when_no_campaigns_found(): void {

		$this->repository
			->shouldReceive( 'get_all' )
			->once()
			->andReturn( [] );

		$result = $this->service->find_all_campaigns();

		$this->assertIsArray( $result );
		$this->assertCount( 0, $result );
	}

	#[Test]
	public function save_campaign_inserts_when_campaign_does_not_exist(): void {

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

		$result = $this->service->save_campaign( $this->make_campaign_dto() );

		$this->assertTrue( $result );
	}

	#[Test]
	public function save_campaign_updates_when_campaign_exists(): void {

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

		$result = $this->service->save_campaign( $this->make_campaign_dto() );

		$this->assertTrue( $result );
	}

	#[Test]
	public function delete_campaign_returns_true_on_success(): void {

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
	public function delete_campaign_returns_false_on_failure(): void {

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
