<?php

declare(strict_types=1);

namespace Fundrik\Core\Support;

use Fundrik\Core\Domain\EntityId;
use Fundrik\Core\Domain\Exceptions\InvalidEntityIdException;
use InvalidArgumentException;
use Stringable;
use TypeError;

/**
 * Utility class for safe and consistent type conversion.
 *
 * Throws exceptions on invalid input instead of silently failing.
 *
 * @since 1.0.0
 */
final readonly class TypeCaster {

	/**
	 * Converts the given value to a boolean.
	 *
	 * Throws if the value cannot be interpreted as a boolean.
	 * Strictly disallows null and empty string.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return bool Converted boolean.
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
	 * Converts the given value to an integer.
	 *
	 * Throws if the value is boolean or not numeric.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return int Converted integer.
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
	 * Converts the given value to a float.
	 *
	 * Throws if the value is boolean or not numeric.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return float Converted float.
	 */
	public static function to_float( mixed $value ): float {

		if ( is_bool( $value ) || ! is_numeric( $value ) ) {
			self::throw_invalid_cast_exception( 'float', $value );
		}

		return (float) $value;
	}

	/**
	 * Converts the given value to a string.
	 *
	 * Throws if the value not scalar and not stringable.
	 *
	 * Empty string is allowed.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return string Trimmed string.
	 */
	public static function to_string( mixed $value ): string {

		if ( is_bool( $value ) ) {
			self::throw_invalid_cast_exception( 'string', $value );
		}

		if ( is_int( $value ) || is_float( $value ) ) {
			self::throw_invalid_cast_exception( 'string', $value, 'numeric' );
		}

		if ( ! is_string( $value ) && ! self::is_stringable_object( $value ) ) {
			self::throw_invalid_cast_exception( 'string', $value );
		}

		return trim( (string) $value );
	}

	/**
	 * Attempts to convert the given value to a scalar type (bool, int, float, or string).
	 *
	 * Throws if the value not scalar.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Input value to convert.
	 *
	 * @return bool|int|float|string The converted scalar value.
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
	 * Converts the given value to a valid entity ID (int or UUID string).
	 *
	 * Internally validates using the EntityId value object.
	 * Throws if the value is not a valid ID format.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return int|string A validated ID.
	 */
	public static function to_id( mixed $value ): int|string {

		try {
			// @phpstan-ignore-next-line
			$entity_id = EntityId::create( $value );
			return $entity_id->value;
		} catch ( InvalidEntityIdException | TypeError $e ) {
			throw new InvalidArgumentException(
				sprintf( 'Cannot cast value to valid entity ID: %s', $e->getMessage() ),
				previous: $e,
			);
		}
	}

	/**
	 * Converts the given value to a valid integer entity ID.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return int Validated integer ID.
	 */
	public static function to_id_int( mixed $value ): int {

		return self::to_int( self::to_id( $value ) );
	}

	/**
	 * Converts the given value to a valid UUID string entity ID.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return string Validated UUID string ID.
	 */
	public static function to_id_uuid( mixed $value ): string {

		return self::to_string( self::to_id( $value ) );
	}

	/**
	 * Checks if a value is an object that can be cast to string.
	 *
	 * In PHP 8+, all objects with __toString() implicitly implement Stringable.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @phpstan-assert-if-true Stringable $value
	 *
	 * @return bool True if the object implements Stringable, false otherwise.
	 */
	private static function is_stringable_object( mixed $value ): bool {

		return $value instanceof Stringable;
	}

	/**
	 * Throws an InvalidArgumentException for an invalid type cast.
	 *
	 * Generates a consistent exception message indicating the source and target types.
	 *
	 * @since 1.0.0
	 *
	 * @param string $target_type The desired target type (e.g., 'int', 'bool', 'string').
	 * @param mixed $value The original value that failed casting.
	 * @param string|null $source_type Optional override for source type description; if null, determined from $value.
	 */
	private static function throw_invalid_cast_exception(
		string $target_type,
		mixed $value,
		?string $source_type = null,
	): never {

		$source_type ??= get_debug_type( $value );

		throw new InvalidArgumentException(
			sprintf(
				'Cannot cast %s to %s',
				$source_type,
				$target_type,
			),
		);
	}
}
