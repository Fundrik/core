<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain\Exceptions;

use InvalidArgumentException;

/**
 * Thrown when an EntityId is invalid.
 *
 * This exception is used to indicate that an entity identifier
 * is either not a valid UUID or not a positive integer, depending on the context.
 *
 * @since 1.0.0
 */
final class InvalidEntityIdException extends InvalidArgumentException {}
