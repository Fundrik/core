<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain\Exceptions;

use InvalidArgumentException;

// phpcs:disable SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.MultipleEmptyLinesAfterOpeningBrace, SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.IncorrectEmptyLinesBeforeClosingBrace
/**
 * Exception thrown when an entity ID is invalid.
 *
 * Typically thrown by EntityId::create when the ID is not a non-empty int or non-empty string.
 *
 * @since 1.0.0
 */
final class InvalidEntityIdException extends InvalidArgumentException {}
// phpcs:enable SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.MultipleEmptyLinesAfterOpeningBrace, SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.IncorrectEmptyLinesBeforeClosingBrace