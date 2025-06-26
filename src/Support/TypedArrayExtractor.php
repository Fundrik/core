<?php

declare(strict_types=1);

namespace Fundrik\Core\Support;

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
final readonly class TypedArrayExtractor {

	/**
	 * Extracts a boolean value from the given array by key.
	 *
	 * If the key exists, attempts to cast the value to bool using TypeCaster.
	 * If the key is missing, or the value is null or invalid, returns null.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return bool|null The boolean value, or null if key is missing or value invalid.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_bool_or_null( array $data, string $key ): ?bool {

		if ( ! array_key_exists( $key, $data ) ) {
			return null;
		}

		try {
			return TypeCaster::to_bool( $data[ $key ] );
		} catch ( InvalidArgumentException ) {
			return null;
		}
	}

	/**
	 * Extracts an integer value from the given array by key.
	 *
	 * If the key exists, attempts to cast the value to int using TypeCaster.
	 * If the key is missing, or the value is null or invalid, returns null.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return int|null The integer value, or null if key is missing or value invalid.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_int_or_null( array $data, string $key ): ?int {

		if ( ! array_key_exists( $key, $data ) ) {
			return null;
		}

		try {
			return TypeCaster::to_int( $data[ $key ] );
		} catch ( InvalidArgumentException ) {
			return null;
		}
	}

	/**
	 * Extracts a string value from the given array by key.
	 *
	 * If the key exists, attempts to cast the value to string using TypeCaster.
	 * If the key is missing, or the value is null or invalid, returns null.
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return string|null The string value, or null if key is missing or value invalid.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_string_or_null( array $data, string $key ): ?string {

		if ( ! array_key_exists( $key, $data ) ) {
			return null;
		}

		try {
			return TypeCaster::to_string( $data[ $key ] );
		} catch ( InvalidArgumentException ) {
			return null;
		}
	}

	/**
	 * Extracts an array value from the given array by key.
	 *
	 * If the key exists and the value is an array, returns it.
	 * Otherwise, returns null (either key missing or value not an array).
	 *
	 * @since 1.0.0
	 *
	 * @param array<mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return array<mixed>|null The array value, or null if key is missing or value is not an array.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_array_or_null( array $data, string $key ): ?array {

		return array_key_exists( $key, $data ) && is_array( $data[ $key ] ) ? $data[ $key ] : null;
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
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_bool_required( array $data, string $key ): bool {

		$value = self::extract_bool_or_null( $data, $key );

		if ( $value === null ) {
			throw new InvalidArgumentException( "Missing or invalid required boolean key '{$key}'" );
		}

		return $value;
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
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_int_required( array $data, string $key ): int {

		$value = self::extract_int_or_null( $data, $key );

		if ( $value === null ) {
			throw new InvalidArgumentException( "Missing or invalid required integer key '{$key}'" );
		}

		return $value;
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
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_string_required( array $data, string $key ): string {

		$value = self::extract_string_or_null( $data, $key );

		if ( $value === null ) {
			throw new InvalidArgumentException( "Missing or invalid required string key '{$key}'" );
		}

		return $value;
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
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_array_required( array $data, string $key ): array {

		$value = self::extract_array_or_null( $data, $key );

		if ( $value === null ) {
			throw new InvalidArgumentException( "Missing or invalid required array key '{$key}'" );
		}

		return $value;
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
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_id_required( array $data, string $key ): int|string {

		if ( ! array_key_exists( $key, $data ) ) {
			throw new InvalidArgumentException( "Missing required ID key '{$key}'" );
		}

		return TypeCaster::to_id( $data[ $key ] );
	}
}
