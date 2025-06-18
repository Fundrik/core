<?php

declare(strict_types=1);

namespace Fundrik\Core\Support;

/**
 * A utility class for type casting.
 *
 * @since 1.0.0
 */
final readonly class TypeCaster {

	/**
	 * Casts a value to boolean.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The value to convert.
	 *
	 * @return bool The converted boolean value.
	 */
	public static function to_bool( mixed $value ): bool {

		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Casts a value to integer.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The value to convert.
	 *
	 * @return int The converted integer value.
	 */
	public static function to_int( mixed $value ): int {

		return (int) $value;
	}

	/**
	 * Casts a value to string.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The value to convert.
	 *
	 * @return string The converted string value.
	 */
	public static function to_string( mixed $value ): string {

		return trim( (string) $value );
	}

	/**
	 * Casts a value to an ID, that can be numeric or UUID-like strings.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The value to convert.
	 *
	 * @return int|string The converted ID as int or string.
	 */
	public static function to_id( mixed $value ): int|string {

		$int_value = filter_var( $value, FILTER_VALIDATE_INT );

		if ( false !== $int_value ) {
			return $int_value;
		}

		return self::to_string( $value );
	}
}
