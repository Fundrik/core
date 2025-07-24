<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Components\Shared\Domain;

use Fundrik\Core\Components\Shared\Domain\EntityId;
use Fundrik\Core\Components\Shared\Domain\Exceptions\InvalidEntityIdException;
use Fundrik\Core\Support\TypeCaster;
use Fundrik\Core\Tests\FundrikTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass( EntityId::class )]
#[UsesClass( TypeCaster::class )]
final class EntityIdTest extends FundrikTestCase {

	#[Test]
	public function creates_from_positive_int(): void {

		$campaign_id = EntityId::create( 123 );

		$this->assertEquals( 123, $campaign_id->value );
	}

	#[Test]
	public function throws_when_negative_int_provided(): void {

		$this->expectException( InvalidEntityIdException::class );
		$this->expectExceptionMessage( 'EntityId must be a positive integer, given: -123' );

		EntityId::create( -123 );
	}

	#[Test]
	public function throws_when_zero_provided(): void {

		$this->expectException( InvalidEntityIdException::class );
		$this->expectExceptionMessage( 'EntityId must be a positive integer, given: 0' );

		EntityId::create( 0 );
	}

	#[Test]
	public function creates_from_valid_uuid(): void {

		$uuid = '0196930b-f2ef-7ec8-b685-cffc19cbf0e3';
		$campaign_id = EntityId::create( $uuid );

		$this->assertEquals( $uuid, $campaign_id->value );
	}

	#[Test]
	public function throws_when_invalid_uuid_provided(): void {

		$this->expectException( InvalidEntityIdException::class );
		$this->expectExceptionMessage( 'EntityId must be a valid UUID, given: invalid-uuid' );

		EntityId::create( 'invalid-uuid' );
	}

	#[Test]
	public function checks_uuid_case_normalization(): void {

		$uuid = '0196A27F-1441-7692-AAEF-92889618FC12';
		$campaign_id = EntityId::create( $uuid );

		$this->assertEquals( '0196a27f-1441-7692-aaef-92889618fc12', $campaign_id->value );
	}
}
