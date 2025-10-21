<?php

declare(strict_types=1);

namespace Fundrik\Core\Support;

use Fundrik\Core\Components\Shared\Domain\EntityId;
use Fundrik\Core\Components\Shared\Domain\Exceptions\InvalidEntityIdException;
use InvalidArgumentException;
use Stringable;
use TypeError;

/**
 * Provides strict casting utilities for transforming raw values into expected scalar types.
 *
 * Enforces predictable behavior by throwing exceptions on invalid input,
 * unlike PHP's native type casts which may silently coerce values.
 *
 * @todo Evaluate moving ID-related logic (to_id*, etc.) to a dedicated Domain ID normalizer.
 *       Current implementation introduces a domain leak into low-level utility code.
 *
 * @since 0.1.0
 */
final readonly class TypeCaster {

	/**
	 * Converts the input to a boolean.
	 *
	 * Throws if the input is null, an empty string, or cannot be interpreted as a boolean.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return bool The converted boolean.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to bool.
	 */
	public static function to_bool( mixed $value ): bool {

		if ( $value === null || $value === '' ) {
			self::throw_invalid_cast_exception( 'bool', $value, 'null or empty string' );
		}

		$result = filter_var( $value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );

		if ( $result === null ) {
			self::throw_invalid_cast_exception( 'bool', $value );
		}

		return $result;
	}

	/**
	 * Converts the input to an integer.
	 *
	 * Throws if the input is a boolean, a float, or not numeric.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return int The converted integer.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to int.
	 */
	public static function to_int( mixed $value ): int {

		if ( is_bool( $value ) || ! is_numeric( $value ) ) {
			self::throw_invalid_cast_exception( 'int', $value );
		}

		if ( is_float( $value ) ) {
			self::throw_invalid_cast_exception( 'int', $value );
		}

		if ( is_string( $value ) && str_contains( $value, '.' ) ) {
			self::throw_invalid_cast_exception( 'int', $value, 'float-like string' );
		}

		return (int) $value;
	}

	/**
	 * Converts the input to a float.
	 *
	 * Throws if the input is a boolean or not numeric.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return float The converted float.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to float.
	 */
	public static function to_float( mixed $value ): float {

		if ( is_bool( $value ) || ! is_numeric( $value ) ) {
			self::throw_invalid_cast_exception( 'float', $value );
		}

		return (float) $value;
	}

	/**
	 * Converts the input to a trimmed string.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return string The converted string.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to string.
	 */
	public static function to_string( mixed $value ): string {

		if ( ! is_string( $value ) && ! self::is_stringable_object( $value ) ) {
			self::throw_invalid_cast_exception( 'string', $value );
		}

		return trim( (string) $value );
	}

	/**
	 * Converts the input to a scalar (bool, int, float, or string).
	 *
	 * Applies multiple conversion attempts in order, throwing if none succeed.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @return bool|int|float|string The converted scalar value.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to a scalar.
	 */
	public static function to_scalar( mixed $value ): bool|int|float|string {

		// phpcs:disable Generic.CodeAnalysis.EmptyStatement.DetectedCatch

		try {
			return self::to_bool( $value );
		} catch ( InvalidArgumentException ) {
			// not bool.
		}

		try {
			return self::to_int( $value );
		} catch ( InvalidArgumentException ) {
			// not int.
		}

		try {
			return self::to_float( $value );
		} catch ( InvalidArgumentException ) {
			// not float.
		}

		try {
			return self::to_string( $value );
		} catch ( InvalidArgumentException ) {
			// not string.
		}

		// phpcs:enable Generic.CodeAnalysis.EmptyStatement.DetectedCatch

		self::throw_invalid_cast_exception( 'scalar', $value );
	}

	/**
	 * Converts the input to a valid entity ID (positive integer or UUID).
	 *
	 * Validates the format using the EntityId value object.
	 * Throws if the input is not a valid ID format.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value The input value to validate.
	 *
	 * @return int|string The normalized entity ID value.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to a valid entity ID.
	 */
	public static function to_id( mixed $value ): int|string {

		try {
			// @phpstan-ignore-next-line
			$entity_id = EntityId::create( $value );
			return $entity_id->get_value();
		} catch ( InvalidEntityIdException | TypeError $e ) {
			throw new InvalidArgumentException(
				sprintf( 'Cannot cast value to valid entity ID: %s', $e->getMessage() ),
				previous: $e,
			);
		}
	}

	/**
	 * Converts the input to a validated integer entity ID.
	 *
	 * Applies domain validation and ensures the result is a positive integer.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value The input value to validate.
	 *
	 * @return int The normalized integer ID.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to an integer entity ID.
	 */
	public static function to_id_int( mixed $value ): int {

		return self::to_int( self::to_id( $value ) );
	}

	/**
	 * Converts the input to a validated UUID entity ID.
	 *
	 * Applies domain validation and ensures the result is a UUID.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value The input value to validate.
	 *
	 * @return string The normalized UUID ID.
	 *
	 * @throws InvalidArgumentException When the value cannot be converted to a UUID entity ID.
	 */
	public static function to_id_uuid( mixed $value ): string {

		return self::to_string( self::to_id( $value ) );
	}

	/**
	 * Checks whether the input is an object that can be cast to string.
	 *
	 * Considers objects implementing __toString() or Stringable.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $value The input value.
	 *
	 * @phpstan-assert-if-true Stringable $value
	 *
	 * @return bool True if the object is stringable.
	 */
	private static function is_stringable_object( mixed $value ): bool {

		return $value instanceof Stringable;
	}

	/**
	 * Throws an exception for a failed type cast.
	 *
	 * Constructs a detailed error message indicating the attempted source and target types.
	 *
	 * @since 0.1.0
	 *
	 * @param string $target_type The target type to cast to (e.g., 'int', 'bool').
	 * @param mixed $value The input value that failed to cast.
	 * @param string|null $source_type The optional source type label; determined automatically if not provided.
	 *
	 * @throws InvalidArgumentException When the cast cannot be performed.
	 */
	private static function throw_invalid_cast_exception(
		string $target_type,
		mixed $value,
		?string $source_type = null,
	): never {

		$source_type ??= get_debug_type( $value );

		throw new InvalidArgumentException(
			sprintf( 'Cannot cast %s to %s.', $source_type, $target_type ),
		);
	}
}
