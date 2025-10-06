<?php

declare(strict_types=1);

namespace Fundrik\Core\Support;

use Fundrik\Core\Support\Exceptions\ArrayExtractionException;
use InvalidArgumentException;

/**
 * Extracts and casts typed values from associative arrays using strict validation.
 *
 * Provides helpers for retrieving values as specific types such as boolean,
 * integer, float, string, scalar, or array. Ensures that the extracted value
 * matches the expected type and throws an exception otherwise.
 *
 * Supports both optional and required extractions, returning null or throwing
 * if the key is missing, depending on the method.
 *
 * @todo Consider moving extract_id_* methods out of this utility.
 *       These methods depend on domain ID validation and may not belong
 *       in a general-purpose array extraction tool.
 *
 * @since 0.1.0
 */
final readonly class ArrayExtractor {

	/**
	 * Extracts the boolean value for the given key from the source array.
	 *
	 * Returns null if the key is missing.
	 *
	 * @since 0.1.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return bool|null The extracted boolean or null.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_bool_optional( array $data, string $key ): ?bool {

		return self::cast_value( $data, $key, TypeCaster::to_bool( ... ), 'bool', required: false );
	}

	/**
	 * Extracts the integer value for the given key from the source array.
	 *
	 * Returns null if the key is missing.
	 *
	 * @since 0.1.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return int|null The extracted integer or null.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_int_optional( array $data, string $key ): ?int {

		return self::cast_value( $data, $key, TypeCaster::to_int( ... ), 'int', required: false );
	}

	/**
	 * Extracts the float value for the given key from the source array.
	 *
	 * Returns null if the key is missing.
	 *
	 * @since 0.1.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return float|null The extracted float or null.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_float_optional( array $data, string $key ): ?float {

		return self::cast_value( $data, $key, TypeCaster::to_float( ... ), 'float', required: false );
	}

	/**
	 * Extracts the string value for the given key from the source array.
	 *
	 * Returns null if the key is missing.
	 *
	 * @since 0.1.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return string|null The extracted string or null.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_string_optional( array $data, string $key ): ?string {

		return self::cast_value( $data, $key, TypeCaster::to_string( ... ), 'string', required: false );
	}

	/**
	 * Extracts the scalar value for the given key from the source array.
	 *
	 * Returns null if the key is missing.
	 *
	 * @since 0.1.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return bool|int|float|string|null The extracted scalar or null.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_scalar_optional( array $data, string $key ): bool|int|float|string|null {

		return self::cast_value( $data, $key, TypeCaster::to_scalar( ... ), 'scalar', required: false );
	}

	/**
	 * Extracts the array value for the given key from the source array.
	 *
	 * Returns null if the key is missing or the value is not an array.
	 *
	 * @since 0.1.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return array<mixed>|null The extracted array or null.
	 *
	 * @throws ArrayExtractionException When the value is present but invalid.
	 */
	public static function extract_array_optional( array $data, string $key ): ?array {

		return self::cast_value(
			$data,
			$key,
			static function ( mixed $value ): array {

				if ( ! is_array( $value ) ) {
					throw new InvalidArgumentException( sprintf( '%s given', get_debug_type( $value ) ) );
				}

				return $value;
			},
			'array',
			required: false,
		);
	}

	/**
	 * Extracts the boolean value for the given key from the source array.
	 *
	 * Throws if the key is missing or the value is invalid.
	 *
	 * @since 0.1.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return bool The extracted boolean.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_bool_required( array $data, string $key ): bool {

		return self::cast_value( $data, $key, TypeCaster::to_bool( ... ), 'bool' );
	}

	/**
	 * Extracts the integer value for the given key from the source array.
	 *
	 * Throws if the key is missing or the value is invalid.
	 *
	 * @since 0.1.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return int The extracted integer.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_int_required( array $data, string $key ): int {

		return self::cast_value( $data, $key, TypeCaster::to_int( ... ), 'int' );
	}

	/**
	 * Extracts the float value for the given key from the source array.
	 *
	 * Throws if the key is missing or the value is invalid.
	 *
	 * @since 0.1.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return float The extracted float.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_float_required( array $data, string $key ): float {

		return self::cast_value( $data, $key, TypeCaster::to_float( ... ), 'float' );
	}

	/**
	 * Extracts the string value for the given key from the source array.
	 *
	 * Throws if the key is missing or the value is invalid.
	 *
	 * @since 0.1.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return string The extracted string.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_string_required( array $data, string $key ): string {

		return self::cast_value( $data, $key, TypeCaster::to_string( ... ), 'string' );
	}

	/**
	 * Extracts the scalar value for the given key from the source array.
	 *
	 * Throws if the key is missing or the value is not a scalar.
	 *
	 * @since 0.1.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return bool|int|float|string The extracted scalar.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_scalar_required( array $data, string $key ): bool|int|float|string {

		return self::cast_value( $data, $key, TypeCaster::to_scalar( ... ), 'scalar' );
	}

	/**
	 * Extracts the array value for the given key from the source array.
	 *
	 * Throws if the key is missing or the value is not an array.
	 *
	 * @since 0.1.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return array<mixed> The extracted array.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_array_required( array $data, string $key ): array {

		return self::cast_value(
			$data,
			$key,
			static function ( mixed $value ): array {

				if ( ! is_array( $value ) ) {
					throw new InvalidArgumentException( sprintf( '%s given', get_debug_type( $value ) ) );
				}

				return $value;
			},
			'array',
		);
	}

	/**
	 * Extracts a validated entity ID (positive integer or UUID) from the source array.
	 *
	 * Throws if the key is missing or the value is not a valid ID format.
	 *
	 * @since 0.1.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to extract.
	 *
	 * @return int|string The normalized entity ID.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
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
	 * Extracts a validated integer entity ID from the source array.
	 *
	 * Throws if the key is missing or the value is not a valid integer ID.
	 *
	 * @since 0.1.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to extract.
	 *
	 * @return int The normalized integer ID.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_id_int_required( array $data, string $key ): int {

		return self::cast_value(
			$data,
			$key,
			static fn ( mixed $value ): int => TypeCaster::to_id_int( $value ),
			'integer entity ID',
		);
	}

	/**
	 * Extracts a validated UUID entity ID from the source array.
	 *
	 * Throws if the key is missing or the value is not a valid UUID.
	 *
	 * @since 0.1.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to extract.
	 *
	 * @return string The normalized UUID ID.
	 *
	 * @throws ArrayExtractionException When the key is missing or the value is invalid.
	 */
	public static function extract_id_uuid_required( array $data, string $key ): string {

		return self::cast_value(
			$data,
			$key,
			static fn ( mixed $value ): string => TypeCaster::to_id_uuid( $value ),
			'UUID entity ID',
		);
	}

	/**
	 * Extracts and casts the value for the given key using the provided caster.
	 *
	 * - Returns null if the key is missing and not required.
	 * - Throws if the key is missing and required.
	 * - Throws if the caster fails to validate the input.
	 *
	 * @since 0.1.0
	 *
	 * @template T
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to extract.
	 * @param callable $caster The function that casts the input.
	 * @param string $type_description The expected type name for error reporting.
	 * @param bool $required Whether the key is required. Default: true.
	 *
	 * @phpstan-param callable(mixed): T $caster
	 *
	 * @phpstan-return ($required is true ? T : T|null)
	 *
	 * @return mixed The extracted and cast value.
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
				throw new ArrayExtractionException( sprintf( "Missing required key '%s'.", $key ) );
			}

			return null;
		}

		try {
			return $caster( $data[ $key ] );
		} catch ( InvalidArgumentException $e ) {
			throw new ArrayExtractionException(
				sprintf(
					"Invalid value at key '%s' (expected %s): %s",
					$key,
					$type_description,
					$e->getMessage(),
				),
				previous: $e,
			);
		}
	}
}
