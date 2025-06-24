<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Support;

use Fundrik\Core\Support\TypeCaster;
use Fundrik\Core\Support\TypedArrayExtractor;
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
		$this->assertFalse( TypedArrayExtractor::extract_bool_or_null( [ 'flag' => null ], 'flag' ) );
		$this->assertNull( TypedArrayExtractor::extract_bool_or_null( [], 'missing_flag' ) );
	}

	#[Test]
	public function it_extracts_int_or_null_correctly(): void {

		$this->assertSame( 123, TypedArrayExtractor::extract_int_or_null( [ 'num' => '123' ], 'num' ) );
		$this->assertSame( 0, TypedArrayExtractor::extract_int_or_null( [ 'num' => 'abc' ], 'num' ) );
		$this->assertSame( 5, TypedArrayExtractor::extract_int_or_null( [ 'num' => 5.99 ], 'num' ) );
		$this->assertSame( 1, TypedArrayExtractor::extract_int_or_null( [ 'num' => true ], 'num' ) );
		$this->assertSame( 0, TypedArrayExtractor::extract_int_or_null( [ 'num' => null ], 'num' ) );
		$this->assertNull( TypedArrayExtractor::extract_int_or_null( [], 'missing_num' ) );
	}

	#[Test]
	public function it_extracts_string_or_null_correctly(): void {

		$this->assertSame( '123', TypedArrayExtractor::extract_string_or_null( [ 'text' => 123 ], 'text' ) );
		$this->assertSame( '1', TypedArrayExtractor::extract_string_or_null( [ 'text' => true ], 'text' ) );
		$this->assertSame( '', TypedArrayExtractor::extract_string_or_null( [ 'text' => null ], 'text' ) );
		$this->assertSame( '5.7', TypedArrayExtractor::extract_string_or_null( [ 'text' => 5.7 ], 'text' ) );
		$this->assertSame( 'text', TypedArrayExtractor::extract_string_or_null( [ 'text' => 'text' ], 'text' ) );
		$this->assertSame( '', TypedArrayExtractor::extract_string_or_null( [ 'text' => '' ], 'text' ) );
		$this->assertNull( TypedArrayExtractor::extract_string_or_null( [], 'missing_text' ) );
	}

	#[Test]
	public function it_extracts_array_or_null_correctly(): void {

		$data = [
			'a' => 1,
			'b' => 2,
		];
		$wrapper = [ 'data' => $data ];

		$this->assertSame( $data, TypedArrayExtractor::extract_array_or_null( $wrapper, 'data' ) );

		$this->assertNull(
			TypedArrayExtractor::extract_array_or_null( [ 'data' => 'not-an-array' ], 'data' ),
		);

		$this->assertNull(
			TypedArrayExtractor::extract_array_or_null( [ 'data' => null ], 'data' ),
		);

		$this->assertNull(
			TypedArrayExtractor::extract_array_or_null( [], 'missing_data' ),
		);
	}

	#[Test]
	public function it_extracts_bool_or_false_when_key_exists_with_various_values(): void {

		$this->assertTrue( TypedArrayExtractor::extract_bool_or_false( [ 'flag' => true ], 'flag' ) );
		$this->assertFalse( TypedArrayExtractor::extract_bool_or_false( [ 'flag' => false ], 'flag' ) );
		$this->assertTrue( TypedArrayExtractor::extract_bool_or_false( [ 'flag' => 1 ], 'flag' ) );
		$this->assertFalse( TypedArrayExtractor::extract_bool_or_false( [ 'flag' => 0 ], 'flag' ) );
		$this->assertTrue( TypedArrayExtractor::extract_bool_or_false( [ 'flag' => 'true' ], 'flag' ) );
		$this->assertFalse( TypedArrayExtractor::extract_bool_or_false( [ 'flag' => 'false' ], 'flag' ) );
		$this->assertFalse( TypedArrayExtractor::extract_bool_or_false( [ 'flag' => null ], 'flag' ) );
	}

	#[Test]
	public function it_extracts_bool_or_false_when_key_missing_returns_false(): void {

		$this->assertFalse( TypedArrayExtractor::extract_bool_or_false( [], 'missing_key' ) );
	}

	#[Test]
	public function it_extracts_int_or_zero_when_key_exists_with_various_values(): void {

		$this->assertSame( 123, TypedArrayExtractor::extract_int_or_zero( [ 'num' => '123' ], 'num' ) );
		$this->assertSame( 0, TypedArrayExtractor::extract_int_or_zero( [ 'num' => 'abc' ], 'num' ) );
		$this->assertSame( 5, TypedArrayExtractor::extract_int_or_zero( [ 'num' => 5.99 ], 'num' ) );
		$this->assertSame( 1, TypedArrayExtractor::extract_int_or_zero( [ 'num' => true ], 'num' ) );
		$this->assertSame( 0, TypedArrayExtractor::extract_int_or_zero( [ 'num' => false ], 'num' ) );
		$this->assertSame( 0, TypedArrayExtractor::extract_int_or_zero( [ 'num' => null ], 'num' ) );
	}

	#[Test]
	public function it_extracts_int_or_zero_when_key_missing_returns_zero(): void {

		$this->assertSame( 0, TypedArrayExtractor::extract_int_or_zero( [], 'missing_key' ) );
	}

	#[Test]
	public function it_extracts_string_or_empty_when_key_exists_with_various_values(): void {

		$this->assertSame( '123', TypedArrayExtractor::extract_string_or_empty( [ 'text' => 123 ], 'text' ) );
		$this->assertSame( '1', TypedArrayExtractor::extract_string_or_empty( [ 'text' => true ], 'text' ) );
		$this->assertSame( '', TypedArrayExtractor::extract_string_or_empty( [ 'text' => null ], 'text' ) );
		$this->assertSame( '5.7', TypedArrayExtractor::extract_string_or_empty( [ 'text' => 5.7 ], 'text' ) );
		$this->assertSame( 'text', TypedArrayExtractor::extract_string_or_empty( [ 'text' => 'text' ], 'text' ) );
		$this->assertSame( '', TypedArrayExtractor::extract_string_or_empty( [ 'text' => '' ], 'text' ) );
	}

	#[Test]
	public function it_extracts_string_or_empty_when_key_missing_returns_empty_string(): void {

		$this->assertSame( '', TypedArrayExtractor::extract_string_or_empty( [], 'missing_key' ) );
	}

	#[Test]
	public function it_extracts_array_or_empty_correctly(): void {

		$this->assertSame(
			[ 'x' => 10 ],
			TypedArrayExtractor::extract_array_or_empty( [ 'meta' => [ 'x' => 10 ] ], 'meta' ),
		);

		$this->assertSame(
			[],
			TypedArrayExtractor::extract_array_or_empty( [ 'meta' => null ], 'meta' ),
		);

		$this->assertSame(
			[],
			TypedArrayExtractor::extract_array_or_empty( [ 'meta' => 'non-array' ], 'meta' ),
		);

		$this->assertSame(
			[],
			TypedArrayExtractor::extract_array_or_empty( [], 'missing_meta' ),
		);
	}
}
