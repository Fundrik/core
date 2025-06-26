<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain;

use Fundrik\Core\Domain\Exceptions\InvalidEntityIdException;
use Fundrik\Core\Support\TypeCaster;
use InvalidArgumentException;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

/**
 * Value Object representing a unique identifier for an entity.
 *
 * Accepts non-empty integers or strings (e.g., UUIDs).
 * Used to strongly type IDs across domain models.
 *
 * @since 1.0.0
 */
final readonly class EntityId {

	/**
	 * Private constructor, use factory method.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $value Validated identifier.
	 */
	private function __construct(
		public int|string $value,
	) {}

	/**
	 * Factory method for creating EntityId.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $value Identifier to validate.
	 *
	 * @return self A valid EntityId instance.
	 */
	public static function create( int|string $value ): self {

		try {
			// First, try to cast the value to an integer and create EntityId from int.
			$int_value = TypeCaster::to_int( $value );
			return self::from_int( $int_value );
		} catch ( InvalidArgumentException ) {

			// If casting to int fails, try casting to string and treat it as UUID.
			try {
				$string_value = TypeCaster::to_string( $value );
				return self::from_uuid( $string_value );
			} catch ( InvalidArgumentException | InvalidEntityIdException $e2 ) {
				// If any error occurs while casting to string or creating UUID,
				// wrap and rethrow as InvalidEntityIdException to normalize exceptions.
				throw new InvalidEntityIdException( $e2->getMessage(), previous: $e2 );
			}
		} catch ( InvalidEntityIdException $e ) {
			// If from_int() throws InvalidEntityIdException (e.g. negative int),
			// just rethrow it directly.
			throw $e;
		}
	}

	/**
	 * Creates an EntityId from a positive integer.
	 *
	 * @since 1.0.0
	 *
	 * @param int $value Positive integer ID.
	 *
	 * @return self A valid EntityId instance.
	 */
	private static function from_int( int $value ): self {

		if ( $value <= 0 ) {
			throw new InvalidEntityIdException( "EntityId must be a positive integer, given: {$value}" );
		}

		return new self( $value );
	}

	/**
	 * Creates an EntityId from a UUID string.
	 *
	 * @since 1.0.0
	 *
	 * @param string $uuid A valid UUID string.
	 *
	 * @return self A valid EntityId instance.
	 */
	private static function from_uuid( string $uuid ): self {

		try {
			return new self( TypeCaster::to_string( Uuid::fromString( $uuid ) ) );
		} catch ( InvalidUuidStringException $e ) {
			// phpcs:ignore SlevomatCodingStandard.Functions.RequireSingleLineCall.RequiredSingleLineCall
			throw new InvalidEntityIdException(
				message: "EntityId must be a valid UUID, given: {$uuid}",
				previous: $e,
			);
		}
	}
}
