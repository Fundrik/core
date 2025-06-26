<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Support;

use Fundrik\Core\Support\TypeCaster;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

#[CoversClass( TypeCaster::class )]
final class TypeCasterTest extends TestCase {

	#[Test]
	#[DataProvider( 'provide_values_for_to_bool' )]
	public function it_casts_to_bool_or_throws(
		mixed $value,
		?bool $expected,
		bool $should_throw,
		?string $expected_message = null,
	): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );

			if ( $expected_message !== null ) {

				if ( is_resource( $value ) ) {
					$pattern = '/' . preg_quote( 'Cannot cast value of type resource', '/' ) . '/';
					$this->expectExceptionMessageMatches( $pattern );
				} else {
					$this->expectExceptionMessage( $expected_message );
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
			[ -1, null, true, 'Cannot cast value of type int to bool' ],
			[ 200, null, true, 'Cannot cast value of type int to bool' ],
			[ -0.99, null, true, 'Cannot cast value of type float to bool' ],
			[ 'true', true, false ],
			[ 'false', false, false ],
			[ 'yes', true, false ],
			[ 'no', false, false ],
			[ '1', true, false ],
			[ '0', false, false ],
			[ '00123', null, true, 'Cannot cast value of type string to bool' ],
			[ 'abc', null, true, 'Cannot cast value of type string to bool' ],
			[ '', null, true, 'Cannot cast null or empty string to bool' ],
			[ null, null, true, 'Cannot cast null or empty string to bool' ],
			[ [], null, true, 'Cannot cast value of type array to bool' ],
			[ [ 'some' => 'value' ], null, true, 'Cannot cast value of type array to bool' ],
			[
				new class() {

					public function __toString(): string {

						return 'stringable-object';
					}
				},
				null,
				true,
				'Cannot cast value of type class@anonymous to bool',
			],
			[ new stdClass(), null, true, 'Cannot cast value of type stdClass to bool' ],
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
			[ fopen( 'php://memory', 'r' ), null, true, 'Cannot cast value of type resource to bool' ],
		];
	}

	#[Test]
	#[DataProvider( 'provide_values_for_to_int' )]
	public function it_casts_to_int_or_throws(
		mixed $value,
		?int $expected,
		bool $should_throw,
		?string $expected_message = null,
	): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );

			if ( $expected_message !== null ) {

				if ( is_resource( $value ) ) {
					$pattern = '/' . preg_quote( 'Cannot cast value of type resource', '/' ) . '/';
					$this->expectExceptionMessageMatches( $pattern );
				} else {
					$this->expectExceptionMessage( $expected_message );
				}
			}
		}

		$result = TypeCaster::to_int( $value );
		$this->assertSame( $expected, $result );
	}

	public static function provide_values_for_to_int(): array {

		return [
			[ '123', 123, false ],
			[ '5.99', 5, false ],
			[ true, null, true, 'Cannot cast value of type bool to int' ],
			[ false, null, true, 'Cannot cast value of type bool to int' ],
			[ '0', 0, false ],
			[ 0, 0, false ],
			[ 456, 456, false ],
			[ 'abc', null, true, 'Cannot cast value of type string to int' ],
			[ '', null, true, 'Cannot cast value of type string to int' ],
			[ null, null, true, 'Cannot cast value of type null to int' ],
			[ [], null, true, 'Cannot cast value of type array to int' ],
			[ new stdClass(), null, true, 'Cannot cast value of type stdClass to int' ],
			[
				new class() {

					public function __toString(): string {

						return '42';
					}
				},
				null,
				true,
				'Cannot cast value of type class@anonymous to int',
			],
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
			[ fopen( 'php://memory', 'r' ), null, true, 'Cannot cast value of type resource to int' ],
		];
	}

	#[Test]
	#[DataProvider( 'provide_values_for_to_string' )]
	public function it_casts_to_string_or_throws(
		mixed $value,
		?string $expected,
		bool $should_throw,
		?string $expected_message = null,
	): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );

			if ( $expected_message !== null ) {

				if ( is_resource( $value ) ) {
					$pattern = '/' . preg_quote( 'Cannot cast value of type resource', '/' ) . '/';
					$this->expectExceptionMessageMatches( $pattern );
				} else {
					$this->expectExceptionMessage( $expected_message );
				}
			}
		}

		$result = TypeCaster::to_string( $value );
		$this->assertSame( $expected, $result );
	}

	public static function provide_values_for_to_string(): array {

		return [
			[ 123, null, true, 'Cannot cast numeric to string' ],
			[ 5.7, null, true, 'Cannot cast numeric to string' ],
			[ true, null, true, 'Cannot cast bool to string' ],
			[ false, null, true, 'Cannot cast bool to string' ],
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
			[ new stdClass(), null, true, 'Cannot cast value of type stdClass to string' ],
			[ null, null, true, 'Cannot cast value of type null to string' ],
			[ [], null, true, 'Cannot cast value of type array to string' ],
			[ [ 'array' ], null, true, 'Cannot cast value of type array to string' ],
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
			[ fopen( 'php://memory', 'r' ), null, true, 'Cannot cast value of type resource to string' ],
		];
	}

	#[Test]
	#[DataProvider( 'provide_values_for_to_id' )]
	public function it_casts_to_id_or_throws(
		mixed $value,
		int|string|null $expected,
		bool $should_throw,
		?string $expected_message_part = null,
	): void {

		if ( $should_throw ) {
			$this->expectException( InvalidArgumentException::class );

			if ( $expected_message_part !== null ) {

				if ( $value === null ) {
					$pattern = '/' . preg_quote( 'Cannot cast value to valid entity ID', '/' ) . '/';
					$this->expectExceptionMessageMatches( $pattern );
				} elseif ( $value === '' ) {
					$pattern = '/' . preg_quote( 'EntityId must be a valid UUID', '/' ) . '/';
					$this->expectExceptionMessageMatches( $pattern );
				} else {
					$this->expectExceptionMessageMatches( '/' . preg_quote( $expected_message_part, '/' ) . '/' );
				}
			}
		}

		$result = TypeCaster::to_id( $value );
		$this->assertSame( $expected, $result );
	}

	public static function provide_values_for_to_id(): array {

		return [
			[ 1, 1, false ],
			[ 123_456, 123_456, false ],
			[ '550e8400-e29b-41d4-a716-446655440000', '550e8400-e29b-41d4-a716-446655440000', false ],
			[ 'not-a-uuid', null, true, 'EntityId must be a valid UUID' ],
			[ 0, null, true, 'EntityId must be a positive integer' ],
			[ -1, null, true, 'EntityId must be a positive integer' ],
			[ '', null, true, 'EntityId::create()' ],
			[ null, null, true, 'EntityId::create()' ],
			[ true, null, true, 'EntityId::create()' ],
			[ false, null, true, 'EntityId::create()' ],
			[ [], null, true, 'EntityId::create()' ],
			[ [ 'id' => 1 ], null, true, 'EntityId::create()' ],
			[ new stdClass(), null, true, 'EntityId::create()' ],
		];
	}
}
