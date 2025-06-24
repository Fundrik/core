<?php

declare(strict_types=1);

namespace Fundrik\Core\Support;

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
	 * If the key exists, casts the value to bool using TypeCaster.
	 * If the key is missing or value is null, returns null.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return bool|null The boolean value, or null if key not present.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_bool_or_null( array $data, string $key ): ?bool {

		return array_key_exists( $key, $data ) ? TypeCaster::to_bool( $data[ $key ] ) : null;
	}

	/**
	 * Extracts an integer value from the given array by key.
	 *
	 * If the key exists, casts the value to int using TypeCaster.
	 * If the key is missing or value is null, returns null.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return int|null The integer value, or null if key not present.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_int_or_null( array $data, string $key ): ?int {

		return array_key_exists( $key, $data ) ? TypeCaster::to_int( $data[ $key ] ) : null;
	}

	/**
	 * Extracts a string value from the given array by key.
	 *
	 * If the key exists, casts the value to string using TypeCaster.
	 * If the key is missing, returns null.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return string|null The string value, or null if key is not present.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_string_or_null( array $data, string $key ): ?string {

		return array_key_exists( $key, $data ) ? TypeCaster::to_string( $data[ $key ] ) : null;
	}

	/**
	 * Extracts an array value from the given array by key.
	 *
	 * If the key exists and the value is an array, returns it.
	 * Otherwise, returns null.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return array<mixed>|null The array value, or null if key is not present or value is not an array.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_array_or_null( array $data, string $key ): ?array {

		return array_key_exists( $key, $data ) && is_array( $data[ $key ] ) ? $data[ $key ] : null;
	}

	/**
	 * Extracts a boolean value from the given array by key.
	 *
	 * If the key exists, casts the value to bool using TypeCaster.
	 * If the key is missing or value is null, returns false.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return bool The boolean value, or false if key not present.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_bool_or_false( array $data, string $key ): bool {

		return self::extract_bool_or_null( $data, $key ) ?? false;
	}

	/**
	 * Extracts an integer value from the given array by key.
	 *
	 * If the key exists, casts the value to int using TypeCaster.
	 * If the key is missing or value is null, returns 0.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return int The integer value, or 0 if key not present.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_int_or_zero( array $data, string $key ): int {

		return self::extract_int_or_null( $data, $key ) ?? 0;
	}

	/**
	 * Extracts a string value from the given array by key.
	 *
	 * If the key exists, casts the value to string using TypeCaster.
	 * If the key is missing, returns an empty string.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return string The string value, or empty string if key is not present.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_string_or_empty( array $data, string $key ): string {

		return self::extract_string_or_null( $data, $key ) ?? '';
	}

	/**
	 * Extracts an array value from the given array by key.
	 *
	 * If the key exists and the value is an array, returns it.
	 * Otherwise, returns an empty array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The source array.
	 * @param string $key The key to look up.
	 *
	 * @return array<mixed> The array value, or an empty array if key is not present or value is not an array.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
	 */
	public static function extract_array_or_empty( array $data, string $key ): array {

		return self::extract_array_or_null( $data, $key ) ?? [];
	}
}
