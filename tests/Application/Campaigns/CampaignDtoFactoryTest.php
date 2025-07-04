<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Application\Campaigns;

use Fundrik\Core\Application\Campaigns\CampaignDto;
use Fundrik\Core\Application\Campaigns\CampaignDtoFactory;
use Fundrik\Core\Application\Campaigns\Exceptions\InvalidCampaignDtoInputException;
use Fundrik\Core\Domain\Campaigns\Campaign;
use Fundrik\Core\Domain\Campaigns\CampaignTarget;
use Fundrik\Core\Domain\Campaigns\CampaignTitle;
use Fundrik\Core\Domain\EntityId;
use Fundrik\Core\Support\ArrayExtractor;
use Fundrik\Core\Support\TypeCaster;
use Fundrik\Core\Tests\FundrikTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass( CampaignDtoFactory::class )]
#[UsesClass( Campaign::class )]
#[UsesClass( EntityId::class )]
#[UsesClass( CampaignTitle::class )]
#[UsesClass( CampaignTarget::class )]
#[UsesClass( ArrayExtractor::class )]
#[UsesClass( TypeCaster::class )]
final class CampaignDtoFactoryTest extends FundrikTestCase {

	private CampaignDtoFactory $dto_factory;

	protected function setUp(): void {

		$this->dto_factory = new CampaignDtoFactory();
	}

	#[Test]
	public function creates_dto_from_array(): void {

		$data = [
			'id' => 123,
			'title' => 'Array Campaign',
			'is_enabled' => true,
			'is_open' => true,
			'has_target' => true,
			'target_amount' => 1_500,
		];

		$dto = $this->dto_factory->from_array( $data );

		$this->assertInstanceOf( CampaignDto::class, $dto );
		$this->assertEquals( 123, $dto->id );
		$this->assertEquals( 'Array Campaign', $dto->title );
		$this->assertTrue( $dto->is_enabled );
		$this->assertTrue( $dto->is_open );
		$this->assertTrue( $dto->has_target );
		$this->assertEquals( 1_500, $dto->target_amount );
	}

	#[Test]
	public function from_array_casts_types_correctly(): void {

		$data = [
			'id' => '789', // string that looks like int.
			'title' => '876', // string that looks like int.
			'is_enabled' => '1', // string that should be cast to bool.
			'is_open' => 0, // int that should be cast to bool.
			'has_target' => 'true', // string to bool.
			'target_amount' => '3000', // string to int.
		];

		$dto = $this->dto_factory->from_array( $data );

		$this->assertSame( 789, $dto->id );
		$this->assertSame( '876', $dto->title );
		$this->assertTrue( $dto->is_enabled );
		$this->assertFalse( $dto->is_open );
		$this->assertTrue( $dto->has_target );
		$this->assertSame( 3_000, $dto->target_amount );
	}

	#[Test]
	#[DataProvider( 'invalid_data_provider' )]
	public function from_array_throws_on_invalid_data( array $invalid_data, string $expected_message ): void {

		$this->expectException( InvalidCampaignDtoInputException::class );

		$this->expectExceptionMessageMatches(
			'/Failed to create CampaignDto from array: ' . preg_quote( $expected_message, '/' ) . '/',
		);

		$this->dto_factory->from_array( $invalid_data );
	}

	#[Test]
	public function creates_dto_from_campaign(): void {

		$campaign = new Campaign(
			id: EntityId::create( 456 ),
			title: CampaignTitle::create( 'Domain Campaign' ),
			is_enabled: false,
			is_open: true,
			target: CampaignTarget::create( is_enabled: false, amount: 0 ),
		);

		$dto = $this->dto_factory->from_campaign( $campaign );

		$this->assertInstanceOf( CampaignDto::class, $dto );
		$this->assertEquals( 456, $dto->id );
		$this->assertEquals( 'Domain Campaign', $dto->title );
		$this->assertFalse( $dto->is_enabled );
		$this->assertTrue( $dto->is_open );
		$this->assertFalse( $dto->has_target );
		$this->assertEquals( 0, $dto->target_amount );
	}

	public static function invalid_data_provider(): array {

		return [
			[
				'invalid_data' => [
					'id' => 'invalid_uuid',
					'title' => 'Valid Title',
					'is_enabled' => true,
					'is_open' => true,
					'has_target' => true,
					'target_amount' => 100,
				],
				'expected_message' => "Invalid value type at key 'id' (expected entity ID): Cannot cast value to valid entity ID: EntityId must be a valid UUID, given: invalid_uuid",
			],
			[
				'invalid_data' => [
					'id' => 123,
					'title' => false,
					'is_enabled' => true,
					'is_open' => true,
					'has_target' => true,
					'target_amount' => 100,
				],
				'expected_message' => "Invalid value type at key 'title' (expected string): Cannot cast bool to string",
			],
			[
				'invalid_data' => [
					'id' => 123,
					'title' => 'Valid',
					'is_enabled' => 'not_bool',
					'is_open' => true,
					'has_target' => true,
					'target_amount' => 100,
				],
				'expected_message' => "Invalid value type at key 'is_enabled' (expected bool): Cannot cast string to bool",
			],
			[
				'invalid_data' => [
					'id' => 123,
					'title' => 'Valid',
					'is_enabled' => true,
					'is_open' => true,
					'has_target' => true,
					'target_amount' => 'NaN',
				],
				'expected_message' => "Invalid value type at key 'target_amount' (expected int): Cannot cast string to int",
			],
		];
	}
}
