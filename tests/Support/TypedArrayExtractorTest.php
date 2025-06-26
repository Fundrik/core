<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Support;

use Fundrik\Core\Support\TypeCaster;
use Fundrik\Core\Support\TypedArrayExtractor;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( TypedArrayExtractor::class )]
#[UsesClass( TypeCaster::class )]
final class TypedArrayExtractorTest extends TestCase {

	#[Test]
	public function it_extracts_bool_or_null_correctly(): void {

		$this->assertTrue( TypedArrayExtractor::extract_bool_or_null( [ 'flag' => true ], 'flag' ) );
		$this->assertFalse( TypedArrayExtractor::extract_bool_or_null( [ 'flag' => false ], 'flag' ) );
		$this->assertTrue( TypedArrayExtractor::extract_bool_or_null( [ 'flag' => 'yes' ], 'flag' ) );
		$this->assertFalse( TypedArrayExtractor::extract_bool_or_null( [ 'flag' => 'no' ], 'flag' ) );
		$this->assertNull(
			TypedArrayExtractor::extract_bool_or_null( [ 'flag' => null ], 'flag' ),
			'Null value should result in null',
		);
		$this->assertNull(
			TypedArrayExtractor::extract_bool_or_null( [ 'flag' => 'not-a-bool' ], 'flag' ),
			'Invalid value should result in null',
		);
		$this->assertNull(
			TypedArrayExtractor::extract_bool_or_null( [], 'missing_flag' ),
			'Missing key should result in null',
		);
	}

	#[Test]
	public function it_extracts_int_or_null_correctly(): void {

		$this->assertSame( 123, TypedArrayExtractor::extract_int_or_null( [ 'num' => '123' ], 'num' ) );
		$this->assertSame( 42, TypedArrayExtractor::extract_int_or_null( [ 'num' => 42 ], 'num' ) );
		$this->assertNull(
			TypedArrayExtractor::extract_int_or_null( [ 'num' => 'abc' ], 'num' ),
			'Invalid int value should result in null',
		);
		$this->assertNull(
			TypedArrayExtractor::extract_int_or_null( [ 'num' => true ], 'num' ),
			'Bool is explicitly disallowed in TypeCaster::to_int',
		);
		$this->assertNull(
			TypedArrayExtractor::extract_int_or_null( [ 'num' => null ], 'num' ),
			'Null value should result in null',
		);
		$this->assertNull(
			TypedArrayExtractor::extract_int_or_null( [], 'missing_num' ),
			'Missing key should result in null',
		);
	}

	#[Test]
	public function it_extracts_string_or_null_correctly(): void {

		$this->assertSame( 'text', TypedArrayExtractor::extract_string_or_null( [ 'text' => 'text' ], 'text' ) );
		$this->assertSame( '', TypedArrayExtractor::extract_string_or_null( [ 'text' => '' ], 'text' ) );
		$this->assertNull(
			TypedArrayExtractor::extract_string_or_null( [ 'text' => 123 ], 'text' ),
			'Numeric to string is disallowed in TypeCaster',
		);
		$this->assertNull(
			TypedArrayExtractor::extract_string_or_null( [ 'text' => true ], 'text' ),
			'Bool to string is disallowed in TypeCaster',
		);
		$this->assertNull(
			TypedArrayExtractor::extract_string_or_null( [ 'text' => null ], 'text' ),
			'Null value should result in null',
		);
		$this->assertNull(
			TypedArrayExtractor::extract_string_or_null( [ 'text' => new \stdClass() ], 'text' ),
			'Invalid string value should result in null',
		);
		$this->assertNull(
			TypedArrayExtractor::extract_string_or_null( [], 'missing_text' ),
			'Missing key should result in null',
		);
	}

	#[Test]
	public function it_extracts_array_or_null_correctly(): void {

		$data = [
			'a' => 1,
			'b' => 2,
		];
		$wrapper = [ 'data' => $data ];

		$this->assertSame( $data, TypedArrayExtractor::extract_array_or_null( $wrapper, 'data' ) );
		$this->assertNull( TypedArrayExtractor::extract_array_or_null( [ 'data' => 'not-an-array' ], 'data' ) );
		$this->assertNull( TypedArrayExtractor::extract_array_or_null( [ 'data' => null ], 'data' ) );
		$this->assertNull( TypedArrayExtractor::extract_array_or_null( [], 'missing_data' ) );
	}

	#[Test]
	public function it_extracts_bool_required_or_throws(): void {

		$this->assertTrue( TypedArrayExtractor::extract_bool_required( [ 'flag' => true ], 'flag' ) );
		$this->assertFalse( TypedArrayExtractor::extract_bool_required( [ 'flag' => false ], 'flag' ) );
		$this->assertTrue( TypedArrayExtractor::extract_bool_required( [ 'flag' => 'yes' ], 'flag' ) );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Missing or invalid required boolean key 'missing_flag'" );
		TypedArrayExtractor::extract_bool_required( [], 'missing_flag' );
	}

	#[Test]
	public function it_extracts_int_required_or_throws(): void {

		$this->assertSame( 123, TypedArrayExtractor::extract_int_required( [ 'num' => '123' ], 'num' ) );
		$this->assertSame( 5, TypedArrayExtractor::extract_int_required( [ 'num' => 5.99 ], 'num' ) );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Missing or invalid required integer key 'missing_num'" );
		TypedArrayExtractor::extract_int_required( [], 'missing_num' );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Missing or invalid required integer key 'num'" );
		TypedArrayExtractor::extract_int_required( [ 'num' => 'abc' ], 'num' );
	}

	#[Test]
	public function it_extracts_string_required_or_throws(): void {

		$this->assertSame( 'text', TypedArrayExtractor::extract_string_required( [ 'text' => 'text' ], 'text' ) );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Missing or invalid required string key 'missing_text'" );
		TypedArrayExtractor::extract_string_required( [], 'missing_text' );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Missing or invalid required string key 'text'" );
		TypedArrayExtractor::extract_string_required(
			[ 'text' => 123 ],
			'text',
		);
	}

	#[Test]
	public function it_extracts_array_required_or_throws(): void {

		$data = [ 'x' => 10 ];
		$this->assertSame( $data, TypedArrayExtractor::extract_array_required( [ 'meta' => $data ], 'meta' ) );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Missing or invalid required array key 'missing_meta'" );
		TypedArrayExtractor::extract_array_required( [], 'missing_meta' );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Missing or invalid required array key 'meta'" );
		TypedArrayExtractor::extract_array_required( [ 'meta' => 'not-an-array' ], 'meta' );
	}

	#[Test]
	public function it_extracts_id_required_or_throws(): void {

		$uuid = '550e8400-e29b-41d4-a716-446655440000';
		$this->assertSame( 123, TypedArrayExtractor::extract_id_required( [ 'id' => 123 ], 'id' ) );
		$this->assertSame( $uuid, TypedArrayExtractor::extract_id_required( [ 'id' => $uuid ], 'id' ) );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Missing required ID key 'id'" );
		TypedArrayExtractor::extract_id_required( [], 'id' );

		try {
			TypedArrayExtractor::extract_id_required( [ 'id' => -1 ], 'id' );
			$this->fail( 'Expected InvalidArgumentException not thrown for negative int ID' );
		} catch ( InvalidArgumentException $e ) {
			$this->assertStringContainsString( 'EntityId must be a positive integer', $e->getMessage() );
		}

		try {
			TypedArrayExtractor::extract_id_required( [ 'id' => 'not-a-uuid' ], 'id' );
			$this->fail( 'Expected InvalidArgumentException not thrown for invalid UUID' );
		} catch ( InvalidArgumentException $e ) {
			$this->assertStringContainsString( 'EntityId must be a valid UUID', $e->getMessage() );
		}

		try {
			TypedArrayExtractor::extract_id_required( [ 'id' => null ], 'id' );
			$this->fail( 'Expected InvalidArgumentException not thrown for null ID' );
		} catch ( InvalidArgumentException $e ) {
			$this->assertIsString( $e->getMessage() );
			$this->assertNotEmpty( $e->getMessage() );
		}
	}
}
