<?php

declare(strict_types=1);

namespace Fundrik\Core\Support\Exceptions;

use InvalidArgumentException;

/**
 * Signals a failure while extracting or casting a value from the source array.
 *
 * Indicates that the extraction process failed due to:
 * - a missing required key,
 * - or an invalid value type during casting.
 *
 * @since 1.0.0
 */
final class ArrayExtractionException extends InvalidArgumentException {}
