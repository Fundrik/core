<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Components\Campaigns\Application;

use Fundrik\Core\Components\Campaigns\Application\CampaignDto;
use Fundrik\Core\Components\Campaigns\Application\CampaignDtoFactory;
use Fundrik\Core\Components\Campaigns\Application\Exceptions\CampaignDtoFactoryException;
use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTarget;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTitle;
use Fundrik\Core\Components\Shared\Domain\EntityId;
use Fundrik\Core\Support\ArrayExtractor;
use Fundrik\Core\Support\TypeCaster;
use Fundrik\Core\Tests\FundrikTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass( CampaignDtoFactory::class )]
#[UsesClass( CampaignDto::class )]
#[UsesClass( EntityId::class )]
#[UsesClass( ArrayExtractor::class )]
#[UsesClass( TypeCaster::class )]
#[UsesClass( Campaign::class )]
#[UsesClass( CampaignTarget::class )]
#[UsesClass( CampaignTitle::class )]
final class CampaignDtoFactoryTest extends FundrikTestCase {

	private CampaignDtoFactory $dto_factory;

	protected function setUp(): void {

		$this->dto_factory = new CampaignDtoFactory();
	}

	#[Test]
	public function creates_dto_from_array(): void {

		$dto = $this->dto_factory->from_array( $this->make_data_array() );

		$this->assertInstanceOf( CampaignDto::class, $dto );
		$this->assertSame( 1, $dto->id );
		$this->assertSame( 'Test Campaign', $dto->title );
		$this->assertTrue( $dto->is_active );
		$this->assertTrue( $dto->is_open );
		$this->assertTrue( $dto->has_target );
		$this->assertSame( 100, $dto->target_amount );
	}

	#[Test]
	public function throws_when_field_has_wrong_type(): void {

		$this->expectException( CampaignDtoFactoryException::class );
		$this->expectExceptionMessageMatches( '/^Cannot create CampaignDto from array:/' );

		$this->dto_factory->from_array(
			$this->make_data_array(
				[
					'title' => false, // Invalid type.
				],
			),
		);
	}

	#[Test]
	public function throws_when_required_field_is_missing(): void {

		$data = $this->make_data_array();
		unset( $data['target_amount'] ); // 'target_amount' is missing.

		$this->expectException( CampaignDtoFactoryException::class );
		$this->expectExceptionMessageMatches( '/^Cannot create CampaignDto from array:/' );

		$this->dto_factory->from_array( $data );
	}

	#[Test]
	public function creates_dto_from_campaign(): void {

		$campaign = $this->make_campaign();
		$dto = $this->dto_factory->from_campaign( $campaign );

		$this->assertInstanceOf( CampaignDto::class, $dto );
		$this->assert_campaign_equals_dto( $campaign, $dto );
	}

	private function make_data_array( array $overrides = [] ): array {

		return array_merge(
			[
				'id' => 1,
				'title' => 'Test Campaign',
				'is_active' => true,
				'is_open' => true,
				'has_target' => true,
				'target_amount' => 100,
			],
			$overrides,
		);
	}
}
