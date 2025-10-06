<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Support;

use Fundrik\Core\Components\Shared\Domain\EntityId;
use Fundrik\Core\Support\ArrayExtractor;
use Fundrik\Core\Support\Exceptions\ArrayExtractionException;
use Fundrik\Core\Support\TypeCaster;
use Fundrik\Core\Tests\FundrikTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass( ArrayExtractor::class )]
#[UsesClass( TypeCaster::class )]
#[UsesClass( EntityId::class )]
final class ArrayExtractorTest extends FundrikTestCase {

	#[Test]
	public function it_extracts_bool_optional_correctly(): void {

		$this->assertTrue( ArrayExtractor::extract_bool_optional( [ 'flag' => true ], 'flag' ) );
		$this->assertFalse( ArrayExtractor::extract_bool_optional( [ 'flag' => false ], 'flag' ) );
		$this->assertTrue( ArrayExtractor::extract_bool_optional( [ 'flag' => '1' ], 'flag' ) );
		$this->assertFalse( ArrayExtractor::extract_bool_optional( [ 'flag' => 0 ], 'flag' ) );
		$this->assertNull( ArrayExtractor::extract_bool_optional( [], 'missing_flag' ) );
	}

	#[Test]
	public function it_throws_on_invalid_bool_optional(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'flag' \(expected bool\):/" );
		ArrayExtractor::extract_bool_optional( [ 'flag' => 'maybe' ], 'flag' );
	}

	#[Test]
	public function it_extracts_int_optional_correctly(): void {

		$this->assertSame( 123, ArrayExtractor::extract_int_optional( [ 'num' => '123' ], 'num' ) );
		$this->assertSame( 42, ArrayExtractor::extract_int_optional( [ 'num' => 42 ], 'num' ) );
		$this->assertNull( ArrayExtractor::extract_int_optional( [], 'missing_num' ) );
	}

	#[Test]
	public function it_throws_on_invalid_int_optional(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'num' \(expected int\):/" );
		ArrayExtractor::extract_int_optional( [ 'num' => 'not-an-int' ], 'num' );
	}

	#[Test]
	public function it_extracts_float_optional_correctly(): void {

		$this->assertSame( 123.45, ArrayExtractor::extract_float_optional( [ 'flt' => '123.45' ], 'flt' ) );
		$this->assertSame( 0.0, ArrayExtractor::extract_float_optional( [ 'flt' => 0 ], 'flt' ) );
		$this->assertSame( 99.0, ArrayExtractor::extract_float_optional( [ 'flt' => 99 ], 'flt' ) );
		$this->assertNull( ArrayExtractor::extract_float_optional( [], 'missing_flt' ) );
	}

	#[Test]
	public function it_throws_on_invalid_float_optional(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'flt' \(expected float\):/" );
		ArrayExtractor::extract_float_optional( [ 'flt' => 'not-a-float' ], 'flt' );
	}

	#[Test]
	public function it_extracts_string_optional_correctly(): void {

		$this->assertSame( 'text', ArrayExtractor::extract_string_optional( [ 'text' => 'text' ], 'text' ) );
		$this->assertSame( '', ArrayExtractor::extract_string_optional( [ 'text' => '' ], 'text' ) );
		$this->assertNull( ArrayExtractor::extract_string_optional( [], 'missing_text' ) );
	}

	#[Test]
	public function it_throws_on_invalid_string_optional(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'text' \(expected string\):/" );
		ArrayExtractor::extract_string_optional( [ 'text' => 123 ], 'text' );
	}

	#[Test]
	public function it_extracts_scalar_optional_correctly(): void {

		$this->assertTrue( ArrayExtractor::extract_scalar_optional( [ 'val' => true ], 'val' ) );
		$this->assertSame( 42, ArrayExtractor::extract_scalar_optional( [ 'val' => '42' ], 'val' ) );
		$this->assertSame( 3.14, ArrayExtractor::extract_scalar_optional( [ 'val' => 3.14 ], 'val' ) );
		$this->assertSame( 'hello', ArrayExtractor::extract_scalar_optional( [ 'val' => 'hello' ], 'val' ) );
		$this->assertNull( ArrayExtractor::extract_scalar_optional( [], 'missing_val' ) );
	}

	#[Test]
	public function it_throws_on_invalid_scalar_optional(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'val' \(expected scalar\):/" );
		ArrayExtractor::extract_scalar_optional( [ 'val' => [] ], 'val' );
	}

	#[Test]
	public function it_extracts_array_optional_correctly(): void {

		$data = [
			'a' => 1,
			'b' => 2,
		];
		$wrapper = [ 'data' => $data ];
		$this->assertSame( $data, ArrayExtractor::extract_array_optional( $wrapper, 'data' ) );
		$this->assertNull( ArrayExtractor::extract_array_optional( [], 'missing_data' ) );
	}

	#[Test]
	public function it_throws_on_invalid_array_optional(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'data' \(expected array\):/" );
		ArrayExtractor::extract_array_optional( [ 'data' => 'not-an-array' ], 'data' );
	}

	#[Test]
	public function it_extracts_bool_required_correctly(): void {

		$this->assertTrue( ArrayExtractor::extract_bool_required( [ 'flag' => true ], 'flag' ) );
		$this->assertFalse( ArrayExtractor::extract_bool_required( [ 'flag' => false ], 'flag' ) );
		$this->assertTrue( ArrayExtractor::extract_bool_required( [ 'flag' => 'yes' ], 'flag' ) );
	}

	#[Test]
	public function it_throws_on_missing_bool_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( "Missing required key 'missing_flag'." );
		ArrayExtractor::extract_bool_required( [], 'missing_flag' );
	}

	#[Test]
	public function it_extracts_int_required_correctly(): void {

		$this->assertSame( 123, ArrayExtractor::extract_int_required( [ 'num' => '123' ], 'num' ) );
	}

	#[Test]
	public function it_throws_on_invalid_int_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'num' \(expected int\):/" );
		ArrayExtractor::extract_int_required( [ 'num' => 5.99 ], 'num' );
	}

	#[Test]
	public function it_throws_on_missing_int_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( "Missing required key 'missing_num'." );
		ArrayExtractor::extract_int_required( [], 'missing_num' );
	}

	#[Test]
	public function it_throws_on_non_numeric_string_int_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'num' \(expected int\):/" );
		ArrayExtractor::extract_int_required( [ 'num' => 'abc' ], 'num' );
	}

	#[Test]
	public function it_extracts_float_required_correctly(): void {

		$this->assertSame( 123.45, ArrayExtractor::extract_float_required( [ 'flt' => '123.45' ], 'flt' ) );
		$this->assertSame( 0.0, ArrayExtractor::extract_float_required( [ 'flt' => 0 ], 'flt' ) );
		$this->assertSame( 99.0, ArrayExtractor::extract_float_required( [ 'flt' => 99 ], 'flt' ) );
	}

	#[Test]
	public function it_throws_on_missing_float_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( "Missing required key 'missing_flt'." );
		ArrayExtractor::extract_float_required( [], 'missing_flt' );
	}

	#[Test]
	public function it_throws_on_invalid_float_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'flt' \(expected float\):/" );
		ArrayExtractor::extract_float_required( [ 'flt' => 'not-a-float' ], 'flt' );
	}

	#[Test]
	public function it_extracts_string_required_correctly(): void {

		$this->assertSame( 'text', ArrayExtractor::extract_string_required( [ 'text' => 'text' ], 'text' ) );
	}

	#[Test]
	public function it_throws_on_missing_string_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( "Missing required key 'missing_text'." );
		ArrayExtractor::extract_string_required( [], 'missing_text' );
	}

	#[Test]
	public function it_throws_on_invalid_string_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'text' \(expected string\):/" );
		ArrayExtractor::extract_string_required( [ 'text' => 123 ], 'text' );
	}

	#[Test]
	public function it_extracts_scalar_required_correctly(): void {

		$this->assertTrue( ArrayExtractor::extract_scalar_required( [ 'val' => true ], 'val' ) );
		$this->assertSame( 42, ArrayExtractor::extract_scalar_required( [ 'val' => '42' ], 'val' ) );
		$this->assertSame( 3.14, ArrayExtractor::extract_scalar_required( [ 'val' => 3.14 ], 'val' ) );
		$this->assertSame( 'hello', ArrayExtractor::extract_scalar_required( [ 'val' => 'hello' ], 'val' ) );
	}

	#[Test]
	public function it_throws_on_missing_scalar_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( "Missing required key 'missing_val'." );
		ArrayExtractor::extract_scalar_required( [], 'missing_val' );
	}

	#[Test]
	public function it_throws_on_invalid_scalar_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'val' \(expected scalar\):/" );
		ArrayExtractor::extract_scalar_required( [ 'val' => [] ], 'val' );
	}

	#[Test]
	public function it_extracts_array_required_correctly(): void {

		$data = [
			'x' => 10,
			'y' => 20,
		];
		$this->assertSame( $data, ArrayExtractor::extract_array_required( [ 'meta' => $data ], 'meta' ) );
	}

	#[Test]
	public function it_throws_on_missing_array_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( "Missing required key 'missing_meta'." );
		ArrayExtractor::extract_array_required( [], 'missing_meta' );
	}

	#[Test]
	public function it_throws_on_invalid_array_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'meta' \(expected array\):/" );
		ArrayExtractor::extract_array_required( [ 'meta' => 'not-an-array' ], 'meta' );
	}

	#[Test]
	public function it_extracts_id_required_correctly(): void {

		$uuid = '550e8400-e29b-41d4-a716-446655440000';
		$this->assertSame( 123, ArrayExtractor::extract_id_required( [ 'id' => 123 ], 'id' ) );
		$this->assertSame( $uuid, ArrayExtractor::extract_id_required( [ 'id' => $uuid ], 'id' ) );
	}

	#[Test]
	public function it_throws_on_missing_id_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( "Missing required key 'id'." );
		ArrayExtractor::extract_id_required( [], 'id' );
	}

	#[Test]
	public function it_throws_on_invalid_id_negative_int(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'id' \(expected entity ID\):/" );
		ArrayExtractor::extract_id_required( [ 'id' => -1 ], 'id' );
	}

	#[Test]
	public function it_throws_on_invalid_id_bad_uuid(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'id' \(expected entity ID\):/" );
		ArrayExtractor::extract_id_required( [ 'id' => 'not-a-uuid' ], 'id' );
	}

	#[Test]
	public function it_throws_on_invalid_id_null(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'id' \(expected entity ID\):/" );
		ArrayExtractor::extract_id_required( [ 'id' => null ], 'id' );
	}

	#[Test]
	public function it_extracts_id_int_required_correctly(): void {

		$this->assertSame( 123, ArrayExtractor::extract_id_int_required( [ 'id' => 123 ], 'id' ) );
		$this->assertSame( 42, ArrayExtractor::extract_id_int_required( [ 'id' => '42' ], 'id' ) );
	}

	#[Test]
	public function it_throws_on_invalid_id_int_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'id' \(expected integer entity ID\):/" );

		ArrayExtractor::extract_id_int_required( [ 'id' => -5 ], 'id' );
	}

	#[Test]
	public function it_throws_on_missing_id_int_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( "Missing required key 'id'." );

		ArrayExtractor::extract_id_int_required( [], 'id' );
	}

	#[Test]
	public function it_extracts_id_uuid_required_correctly(): void {

		$uuid = '550e8400-e29b-41d4-a716-446655440000';
		$this->assertSame( $uuid, ArrayExtractor::extract_id_uuid_required( [ 'id' => $uuid ], 'id' ) );
	}

	#[Test]
	public function it_throws_on_invalid_id_uuid_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key 'id' \(expected UUID entity ID\):/" );

		ArrayExtractor::extract_id_uuid_required( [ 'id' => 'not-a-uuid' ], 'id' );
	}

	#[Test]
	public function it_throws_on_missing_id_uuid_required(): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessage( "Missing required key 'id'." );

		ArrayExtractor::extract_id_uuid_required( [], 'id' );
	}

	#[Test]
	#[DataProvider( 'optional_methods_that_throw_on_null' )]
	public function optional_method_throws_on_null_value( callable $method, string $key ): void {

		$this->expectException( ArrayExtractionException::class );
		$this->expectExceptionMessageMatches( "/^Invalid value at key '{$key}' \(expected .+\):/" );

		$method( [ $key => null ], $key );
	}

	public static function optional_methods_that_throw_on_null(): array {

		return [
			[ ArrayExtractor::extract_bool_optional( ... ), 'flag' ],
			[ ArrayExtractor::extract_int_optional( ... ), 'num' ],
			[ ArrayExtractor::extract_float_optional( ... ), 'flt' ],
			[ ArrayExtractor::extract_string_optional( ... ), 'text' ],
			[ ArrayExtractor::extract_scalar_optional( ... ), 'val' ],
			[ ArrayExtractor::extract_array_optional( ... ), 'arr' ],
		];
	}
}
