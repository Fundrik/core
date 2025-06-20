<?php

declare(strict_types=1);

namespace Fundrik\Core\Support;

/**
 * Utility class for safe and consistent type conversion across application layers.
 *
 * Facilitates mapping of raw infrastructure data to strongly typed domain values.
 * Supports conversion to boolean, integer, string, and ID (int or UUID-like string).
 *
 * @since 1.0.0
 */
final readonly class TypeCaster {

	/**
	 * Converts a value to boolean.
	 *
	 * Uses PHP's filter_var with FILTER_VALIDATE_BOOLEAN to interpret common boolean representations.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return bool Converted boolean.
	 */
	public static function to_bool( mixed $value ): bool {

		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Converts a value to integer.
	 *
	 * Casts value using (int) operator.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return int Converted integer.
	 */
	public static function to_int( mixed $value ): int {

		return (int) $value;
	}

	/**
	 * Converts a value to string.
	 *
	 * Casts value to string and trims whitespace.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return string Trimmed string.
	 */
	public static function to_string( mixed $value ): string {

		return trim( (string) $value );
	}

	/**
	 * Converts a value to an ID representation.
	 *
	 * Returns an integer if the value is a valid integer string,
	 * otherwise returns a trimmed string (e.g., UUID).
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return int|string Integer ID or string identifier.
	 */
	public static function to_id( mixed $value ): int|string {

		$int_value = filter_var( $value, FILTER_VALIDATE_INT );

		if ( false !== $int_value ) {
			return $int_value;
		}

		return self::to_string( $value );
	}
}
