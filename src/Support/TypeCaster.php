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
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
	 */
	public static function to_bool( mixed $value ): bool {

		if ( $value === null || $value === '' ) {
			throw new InvalidArgumentException(
				sprintf( 'Cannot cast null or empty string to bool' ),
			);
		}

		$result = filter_var( $value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );

		if ( $result === null ) {
			throw new InvalidArgumentException(
				sprintf( 'Cannot cast value of type %s to bool', get_debug_type( $value ) ),
			);
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
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
	 */
	public static function to_int( mixed $value ): int {

		if ( is_bool( $value ) || ! is_numeric( $value ) ) {
			throw new InvalidArgumentException(
				sprintf( 'Cannot cast value of type %s to int', get_debug_type( $value ) ),
			);
		}

		return (int) $value;
	}

	/**
	 * Converts the given value to a string.
	 *
	 * Throws if the value is boolean,
	 * or if not scalar and not stringable.
	 *
	 * Empty string is allowed.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return string Trimmed string.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
	 */
	public static function to_string( mixed $value ): string {

		if ( is_bool( $value ) ) {
			throw new InvalidArgumentException(
				sprintf( 'Cannot cast bool to string' ),
			);
		}

		if ( is_int( $value ) || is_float( $value ) ) {
			throw new InvalidArgumentException(
				sprintf( 'Cannot cast numeric to string' ),
			);
		}

		if ( is_string( $value ) || self::is_stringable_object( $value ) ) {
			// phpcs:ignore SlevomatCodingStandard.Commenting.InlineDocCommentDeclaration.MissingVariable, Generic.Commenting.DocComment.MissingShort
			/** @var string|Stringable $value */
			return trim( (string) $value );
		}

		throw new InvalidArgumentException(
			sprintf( 'Cannot cast value of type %s to string', get_debug_type( $value ) ),
		);
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
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
	 */
	public static function to_id( mixed $value ): int|string {

		try {
			// phpcs:ignore SlevomatCodingStandard.Commenting.InlineDocCommentDeclaration.MissingVariable, Generic.Commenting.DocComment.MissingShort
			/** @var int|string $value */
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
	 * Checks if a value is an object that can be cast to string.
	 *
	 * In PHP 8+, all objects with __toString() implicitly implement Stringable.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Input value.
	 *
	 * @return bool True if the object implements Stringable, false otherwise.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
	 */
	private static function is_stringable_object( mixed $value ): bool {

		return $value instanceof Stringable;
	}
}
