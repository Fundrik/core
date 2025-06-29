<?php

declare(strict_types=1);

namespace Fundrik\Core\Support;

use Fundrik\Core\Support\Exceptions\ArrayExtractionException;
use InvalidArgumentException;

/**
 * Helper class for safely extracting typed values from associative arrays
 * with options to return default values or null when keys are missing.
 *
 * Provides convenience methods to extract boolean, integer, and string values,
 * either returning sensible defaults (false, 0, '') or nullable values.
 *
 * This ensures that extracted values always have consistent types for safer usage.
 *
 * @since 1.0.0
 */
final readonly class ArrayExtractor {

	/**
	 * Extracts a boolean value from the given array by key.
	 *
	 * If the key exists, attempts to cast the value to bool using TypeCaster.
	 * If the key is missing, returns null.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return bool|null The boolean value, or null if key is missing or value invalid.
	 */
	public static function extract_bool_optional( array $data, string $key ): ?bool {

		return self::cast_value( $data, $key, TypeCaster::to_bool( ... ), 'bool', required: false );
	}

	/**
	 * Extracts an integer value from the given array by key.
	 *
	 * If the key exists, attempts to cast the value to int using TypeCaster.
	 * If the key is missing, returns null.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return int|null The integer value, or null if key is missing or value invalid.
	 */
	public static function extract_int_optional( array $data, string $key ): ?int {

		return self::cast_value( $data, $key, TypeCaster::to_int( ... ), 'int', required: false );
	}

	/**
	 * Extracts a float value from the given array by key.
	 *
	 * If the key exists, attempts to cast the value to float using TypeCaster.
	 * If the key is missing, returns null.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return float|null The float value, or null if key is missing or value invalid.
	 */
	public static function extract_float_optional( array $data, string $key ): ?float {

		return self::cast_value( $data, $key, TypeCaster::to_float( ... ), 'float', required: false );
	}

	/**
	 * Extracts a string value from the given array by key.
	 *
	 * If the key exists, attempts to cast the value to string using TypeCaster.
	 * If the key is missing, returns null.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return string|null The string value, or null if key is missing or value invalid.
	 */
	public static function extract_string_optional( array $data, string $key ): ?string {

		return self::cast_value( $data, $key, TypeCaster::to_string( ... ), 'string', required: false );
	}

	/**
	 * Extracts a scalar value from the given array by key.
	 *
	 * If the key is missing, returns null.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return bool|int|float|string|null The scalar value, or null if key is missing or value invalid.
	 */
	public static function extract_scalar_optional( array $data, string $key ): bool|int|float|string|null {

		return self::cast_value( $data, $key, TypeCaster::to_scalar( ... ), 'scalar', required: false );
	}

	/**
	 * Extracts an array value from the given array by key.
	 *
	 * If the key exists and the value is an array, returns it.
	 * If the key is missing, returns null.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return array<mixed>|null The array value, or null if key is missing or value is not an array.
	 */
	public static function extract_array_optional( array $data, string $key ): ?array {

		return self::cast_value(
			$data,
			$key,
			static function ( mixed $value ): array {

				if ( ! is_array( $value ) ) {
					throw new InvalidArgumentException( get_debug_type( $value ) . ' given' );
				}

				return $value;
			},
			'array',
			required: false,
		);
	}

	/**
	 * Extracts a boolean value from the given array by key.
	 *
	 * Throws an exception if the key is missing or the value is invalid.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return bool The boolean value.
	 */
	public static function extract_bool_required( array $data, string $key ): bool {

		return self::cast_value( $data, $key, TypeCaster::to_bool( ... ), 'bool' );
	}

	/**
	 * Extracts an integer value from the given array by key.
	 *
	 * Throws an exception if the key is missing or the value is invalid.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return int The integer value.
	 */
	public static function extract_int_required( array $data, string $key ): int {

		return self::cast_value( $data, $key, TypeCaster::to_int( ... ), 'int' );
	}

	/**
	 * Extracts a float value from the given array by key.
	 *
	 * Throws an exception if the key is missing or the value is invalid.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return float The float value.
	 */
	public static function extract_float_required( array $data, string $key ): float {

		return self::cast_value( $data, $key, TypeCaster::to_float( ... ), 'float' );
	}

	/**
	 * Extracts a string value from the given array by key.
	 *
	 * Throws an exception if the key is missing or the value is invalid.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return string The string value.
	 */
	public static function extract_string_required( array $data, string $key ): string {

		return self::cast_value( $data, $key, TypeCaster::to_string( ... ), 'string' );
	}

	/**
	 * Extracts a scalar value from the given array by key.
	 *
	 * Throws an exception if the key is missing or the value is not a scalar.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return bool|int|float|string The scalar value.
	 */
	public static function extract_scalar_required( array $data, string $key ): bool|int|float|string {

		return self::cast_value( $data, $key, TypeCaster::to_scalar( ... ), 'scalar' );
	}

	/**
	 * Extracts an array value from the given array by key.
	 *
	 * Throws an exception if the key is missing or the value is not an array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return array<mixed> The array value.
	 */
	public static function extract_array_required( array $data, string $key ): array {

		return self::cast_value(
			$data,
			$key,
			static function ( mixed $value ): array {

				if ( ! is_array( $value ) ) {
					throw new InvalidArgumentException( get_debug_type( $value ) . ' given' );
				}

				return $value;
			},
			'array',
		);
	}

	/**
	 * Extracts an ID value from the given array by key.
	 *
	 * Throws an exception if the key is missing or the value is not a valid entity ID.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return int|string The validated entity ID.
	 */
	public static function extract_id_required( array $data, string $key ): int|string {

		return self::cast_value(
			$data,
			$key,
			static fn ( mixed $value ): int|string => TypeCaster::to_id( $value ),
			'entity ID',
		);
	}

	/**
	 * Common method to extract and cast a value from an array using a provided caster.
	 *
	 * - If the key is missing:
	 *   - Throws exception if $required is true.
	 *   - Returns null if $required is false.
	 *
	 * - If the key exists but the value is of invalid type:
	 *   - Always throws exception with detailed context.
	 *
	 * This method provides consistent error reporting for both optional and required extraction use cases.
	 *
	 * @since 1.0.0
	 *
	 * @template T
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to extract.
	 * @param callable $caster A function that attempts to cast the value.
	 * @param string $type_description Human-readable description of the expected type.
	 * @param bool $required Whether the key must exist in the array. Default: true.
	 *
	 * @phpstan-param callable(mixed): T $caster
	 *
	 * @phpstan-return ($required is true ? T : T|null)
	 *
	 * @return mixed The casted value, or null if not required and key is missing.
	 */
	private static function cast_value(
		array $data,
		string $key,
		callable $caster,
		string $type_description,
		bool $required = true,
	): mixed {

		if ( ! array_key_exists( $key, $data ) ) {

			if ( $required ) {
				throw new ArrayExtractionException( "Missing required key '{$key}'" );
			}

			return null;
		}

		try {
			return $caster( $data[ $key ] );
		} catch ( InvalidArgumentException $e ) {
			throw new ArrayExtractionException(
				"Invalid value type at key '{$key}' (expected {$type_description}): " . $e->getMessage(),
				previous: $e,
			);
		}
	}
}
