<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain;

use Fundrik\Core\Domain\Exceptions\InvalidEntityIdException;
use Fundrik\Core\Support\TypeCaster;
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
		public int|string $value
	) {}

	/**
	 * Factory method for creating EntityId.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $value Identifier to validate.
	 *
	 * @return self A valid EntityId instance.
	 *
	 * @throws InvalidEntityIdException If ID is empty or invalid type.
	 */
	public static function create( int|string $value ): self {

		if ( is_int( $value ) ) {
			return self::from_int( $value );
		}

		if ( is_string( $value ) ) {
			return self::from_uuid( $value );
		}

		// @codeCoverageIgnoreStart
		throw new InvalidEntityIdException( 'EntityId must be int or UUID string' );
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Creates an EntityId from a positive integer.
	 *
	 * @since 1.0.0
	 *
	 * @param int $value Positive integer ID.
	 *
	 * @return self A valid EntityId instance.
	 *
	 * @throws InvalidEntityIdException If the value is not positive.
	 */
	private static function from_int( int $value ): self {

		if ( $value <= 0 ) {
			throw new InvalidEntityIdException( "EntityId must be a positive, given: {$value}" );
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
	 *
	 * @throws InvalidEntityIdException If the UUID is malformed.
	 */
	private static function from_uuid( string $uuid ): self {

		try {
			return new self( TypeCaster::to_string( Uuid::fromString( $uuid ) ) );
		} catch ( InvalidUuidStringException $e ) {
			throw new InvalidEntityIdException(
				message: "EntityId must be a valid UUID, given: {$uuid}",
				previous: $e
			);
		}
	}
}
