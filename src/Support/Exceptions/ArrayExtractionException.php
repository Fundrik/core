<?php

declare(strict_types=1);

namespace Fundrik\Core\Support\Exceptions;

use InvalidArgumentException;

/**
 * Thrown when a value cannot be extracted or cast from the source array.
 *
 * @since 0.1.0
 */
final class ArrayExtractionException extends InvalidArgumentException {}
