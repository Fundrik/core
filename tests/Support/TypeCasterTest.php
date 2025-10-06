<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Support;

use Fundrik\Core\Components\Shared\Domain\EntityId;
use Fundrik\Core\Support\TypeCaster;
use Fundrik\Core\Tests\FundrikTestCase;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use stdClass;

#[CoversClass( TypeCaster::class )]
#[UsesClass( EntityId::class )]
final class TypeCasterTest extends FundrikTestCase {

	#[Test]
	#[DataProvider( 'provide_values_for_to_bool' )]
	public function it_casts_to_bool_or_throws(
		mixed $value,
		?bool $expected,
		bool $should_throw,
		?string $expected_source_type = null,
		?string $expected_target_type = null,
	): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );

			if ( $expected_source_type !== null && $expected_target_type !== null ) {

				if ( is_resource( $value ) ) {
					$pattern = '/Cannot cast resource(?: \([a-z]+\))? to bool/';
					$this->expectExceptionMessageMatches( $pattern );
				} else {
					$this->expectExceptionMessage(
						self::formatInvalidCastMessage( $expected_source_type, $expected_target_type ),
					);
				}
			}
		}

		$result = TypeCaster::to_bool( $value );
		$this->assertSame( $expected, $result );
	}

	public static function provide_values_for_to_bool(): array {

		return [
			[ true, true, false ],
			[ false, false, false ],
			[ 1, true, false ],
			[ 0, false, false ],
			[ -1, null, true, 'int', 'bool' ],
			[ 200, null, true, 'int', 'bool' ],
			[ -0.99, null, true, 'float', 'bool' ],
			[ 'true', true, false ],
			[ 'false', false, false ],
			[ 'yes', true, false ],
			[ 'no', false, false ],
			[ '1', true, false ],
			[ '0', false, false ],
			[ '00123', null, true, 'string', 'bool' ],
			[ 'abc', null, true, 'string', 'bool' ],
			[ '', null, true, 'null or empty string', 'bool' ],
			[ null, null, true, 'null or empty string', 'bool' ],
			[ [], null, true, 'array', 'bool' ],
			[ [ 'some' => 'value' ], null, true, 'array', 'bool' ],
			[
				new class() {

					public function __toString(): string {

						return 'stringable-object';
					}
				},
				null,
				true,
				'class@anonymous',
				'bool',
			],
			[ new stdClass(), null, true, 'stdClass', 'bool' ],
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
			[ fopen( 'php://memory', 'r' ), null, true, 'resource', 'bool' ],
		];
	}

	#[Test]
	#[DataProvider( 'provide_values_for_to_int' )]
	public function it_casts_to_int_or_throws(
		mixed $value,
		?int $expected,
		bool $should_throw,
		?string $expected_source_type = null,
		?string $expected_target_type = null,
	): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );

			if ( $expected_source_type !== null && $expected_target_type !== null ) {

				if ( is_resource( $value ) ) {
					$pattern = '/Cannot cast resource(?: \([a-z]+\))? to int/';
					$this->expectExceptionMessageMatches( $pattern );
				} else {
					$this->expectExceptionMessage(
						self::formatInvalidCastMessage( $expected_source_type, $expected_target_type ),
					);
				}
			}
		}

		$result = TypeCaster::to_int( $value );
		$this->assertSame( $expected, $result );
	}

	public static function provide_values_for_to_int(): array {

		return [
			[ '123', 123, false ],
			[ '5.99', null, true, 'float-like string', 'int' ],
			[ '5.0', null, true, 'float-like string', 'int' ],
			[ true, null, true, 'bool', 'int' ],
			[ false, null, true, 'bool', 'int' ],
			[ '0', 0, false ],
			[ 0, 0, false ],
			[ 456, 456, false ],
			[ 456.0, null, true, 'float', 'int' ],
			[ 'abc', null, true, 'string', 'int' ],
			[ '', null, true, 'string', 'int' ],
			[ null, null, true, 'null', 'int' ],
			[ [], null, true, 'array', 'int' ],
			[ new stdClass(), null, true, 'stdClass', 'int' ],
			[
				new class() {

					public function __toString(): string {

						return '42';
					}
				},
				null,
				true,
				'class@anonymous',
				'int',
			],
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
			[ fopen( 'php://memory', 'r' ), null, true, 'resource', 'int' ],
		];
	}

	#[Test]
	#[DataProvider( 'provide_values_for_to_float' )]
	public function it_casts_to_float_or_throws(
		mixed $value,
		?float $expected,
		bool $should_throw,
		?string $expected_source_type = null,
		?string $expected_target_type = null,
	): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );

			if ( $expected_source_type !== null && $expected_target_type !== null ) {

				if ( is_resource( $value ) ) {
					$pattern = '/Cannot cast resource(?: \([a-z]+\))? to float/';
					$this->expectExceptionMessageMatches( $pattern );
				} else {
					$this->expectExceptionMessage(
						self::formatInvalidCastMessage( $expected_source_type, $expected_target_type ),
					);
				}
			}
		}

		$result = TypeCaster::to_float( $value );
		$this->assertSame( $expected, $result );
	}

	public static function provide_values_for_to_float(): array {

		return [
			[ '123', 123.0, false ],
			[ '5.99', 5.99, false ],
			[ true, null, true, 'bool', 'float' ],
			[ false, null, true, 'bool', 'float' ],
			[ '0', 0.0, false ],
			[ 0, 0.0, false ],
			[ 456.78, 456.78, false ],
			[ 'abc', null, true, 'string', 'float' ],
			[ '', null, true, 'string', 'float' ],
			[ null, null, true, 'null', 'float' ],
			[ [], null, true, 'array', 'float' ],
			[ new stdClass(), null, true, 'stdClass', 'float' ],
			[
				new class() {

					public function __toString(): string {

						return '42.5';
					}
				},
				null,
				true,
				'class@anonymous',
				'float',
			],
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
			[ fopen( 'php://memory', 'r' ), null, true, 'resource', 'float' ],
		];
	}

	#[Test]
	#[DataProvider( 'provide_values_for_to_string' )]
	public function it_casts_to_string_or_throws(
		mixed $value,
		?string $expected,
		bool $should_throw,
		?string $expected_source_type = null,
		?string $expected_target_type = null,
	): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );

			if ( $expected_source_type !== null && $expected_target_type !== null ) {

				if ( is_resource( $value ) ) {
					$pattern = '/Cannot cast resource(?: \([a-z]+\))? to string/';
					$this->expectExceptionMessageMatches( $pattern );
				} else {
					$this->expectExceptionMessage(
						self::formatInvalidCastMessage( $expected_source_type, $expected_target_type ),
					);
				}
			}
		}

		$result = TypeCaster::to_string( $value );
		$this->assertSame( $expected, $result );
	}

	public static function provide_values_for_to_string(): array {

		return [
			[ 123, null, true, 'int', 'string' ],
			[ 5.7, null, true, 'float', 'string' ],
			[ true, null, true, 'bool', 'string' ],
			[ false, null, true, 'bool', 'string' ],
			[ 'text', 'text', false ],
			[ '  text  ', 'text', false ],
			[ '', '', false ],
			[
				new class() {

					public function __toString(): string {

						return 'stringable-object';
					}
				},
				'stringable-object',
				false,
			],
			[ new stdClass(), null, true, 'stdClass', 'string' ],
			[ null, null, true, 'null', 'string' ],
			[ [], null, true, 'array', 'string' ],
			[ [ 'array' ], null, true, 'array', 'string' ],
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
			[ fopen( 'php://memory', 'r' ), null, true, 'resource', 'string' ],
		];
	}

	#[Test]
	#[DataProvider( 'provide_values_for_to_scalar' )]
	public function it_casts_to_scalar_or_throws(
		mixed $value,
		bool|int|float|string|null $expected,
		bool $should_throw,
		?string $expected_source_type = null,
		?string $expected_target_type = null,
	): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );

			if ( $expected_source_type !== null && $expected_target_type !== null ) {

				if ( is_resource( $value ) ) {
					$pattern = '/Cannot cast resource(?: \([a-z]+\))? to scalar/';
					$this->expectExceptionMessageMatches( $pattern );
				} else {
					$this->expectExceptionMessage(
						self::formatInvalidCastMessage( $expected_source_type, $expected_target_type ),
					);
				}
			}
		}

		$result = TypeCaster::to_scalar( $value );
		$this->assertSame( $expected, $result );
	}

	public static function provide_values_for_to_scalar(): array {

		return [
			// bool.
			[ true, true, false ],
			[ 'true', true, false ],
			[ 1, true, false ],

			// int.
			[ '123', 123, false ],

			// float.
			[ '5.6', 5.6, false ],

			// string.
			[ 'abc', 'abc', false ],
			[ '  xyz  ', 'xyz', false ],

			// failures.
			[ [], null, true, 'array', 'scalar' ],
			[ new stdClass(), null, true, 'stdClass', 'scalar' ],
			[ null, null, true, 'null', 'scalar' ],
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
			[ fopen( 'php://memory', 'r' ), null, true, 'resource', 'scalar' ],
		];
	}

	#[Test]
	#[DataProvider( 'provide_values_for_to_id' )]
	public function it_casts_to_id_or_throws( mixed $value, int|string|null $expected, bool $should_throw ): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );
			$this->expectExceptionMessageMatches( '/Cannot cast value to valid entity ID/' );
		}

		$result = TypeCaster::to_id( $value );
		$this->assertSame( $expected, $result );
	}

	public static function provide_values_for_to_id(): array {

		return [
			[ 1, 1, false ],
			[ 123_456, 123_456, false ],
			[ '550e8400-e29b-41d4-a716-446655440000', '550e8400-e29b-41d4-a716-446655440000', false ],
			[ 'not-a-uuid', null, true ],
			[ 0, null, true ],
			[ -1, null, true ],
			[ '', null, true ],
			[ null, null, true ],
			[ true, null, true ],
			[ false, null, true ],
			[ [], null, true ],
			[ [ 'id' => 1 ], null, true ],
			[ new stdClass(), null, true ],
		];
	}

	#[Test]
	#[DataProvider( 'provide_values_for_to_id_int' )]
	public function it_casts_to_id_int_or_throws( mixed $value, ?int $expected, bool|string $should_throw = false ): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );

			$message = is_string( $should_throw )
				? $should_throw
				: 'Cannot cast value to valid entity ID';

			$this->expectExceptionMessageMatches( '/' . $message . '/' );
		}

		$result = TypeCaster::to_id_int( $value );
		$this->assertSame( $expected, $result );
	}

	public static function provide_values_for_to_id_int(): array {

		return [
			[ 1, 1 ],
			[ 123_456, 123_456 ],
			[ '789', 789 ],

			[ '550e8400-e29b-41d4-a716-446655440000', null, 'Cannot cast string to int' ],
			[ 0, null, true ],
			[ -1, null, true ],
			[ '-10', null, true ],
			[ '5.5', null, true ],
			[ 5.0, null, true ],
			[ 'abc', null, true ],
			[ null, null, true ],
			[ [], null, true ],
			[ new stdClass(), null, true ],
		];
	}

	#[Test]
	#[DataProvider( 'provide_values_for_to_id_uuid' )]
	public function it_casts_to_id_uuid_or_throws(
		mixed $value,
		?string $expected,
		bool|string $should_throw = false,
	): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );

			$message = is_string( $should_throw ) ? $should_throw : 'Cannot cast value to valid entity ID';

			$this->expectExceptionMessageMatches( '/' . $message . '/' );
		}

		$result = TypeCaster::to_id_uuid( $value );
		$this->assertSame( $expected, $result );
	}

	public static function provide_values_for_to_id_uuid(): array {

		return [
			[ '550e8400-e29b-41d4-a716-446655440000', '550e8400-e29b-41d4-a716-446655440000' ],
			[ '00000000-0000-0000-0000-000000000000', '00000000-0000-0000-0000-000000000000' ],

			[ 123, null, 'Cannot cast int to string' ],
			[ 'not-a-uuid', null, true ],
			[ '', null, true ],
			[ null, null, true ],
			[ [], null, true ],
			[ new \stdClass(), null, true ],
		];
	}

	private static function formatInvalidCastMessage( string $source_type, string $target_type ): string {

		return sprintf( 'Cannot cast %s to %s.', $source_type, $target_type );
	}
}
