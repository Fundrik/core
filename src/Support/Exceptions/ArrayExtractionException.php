<?php

declare(strict_types=1);

namespace Fundrik\Core\Support\Exceptions;

use InvalidArgumentException;

// phpcs:disable SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.MultipleEmptyLinesAfterOpeningBrace, SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.IncorrectEmptyLinesBeforeClosingBrace
/**
 * Exception thrown when a value cannot be extracted or casted from an array.
 *
 * Typically used to indicate:
 * - Missing required keys.
 * - Invalid value types during casting.
 *
 * This exception wraps lower-level casting errors (e.g. from TypeCaster) and provides
 * clear context about which key caused the failure.
 *
 * @since 1.0.0
 */
final class ArrayExtractionException extends InvalidArgumentException {}
// phpcs:enable SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.MultipleEmptyLinesAfterOpeningBrace, SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.IncorrectEmptyLinesBeforeClosingBrace