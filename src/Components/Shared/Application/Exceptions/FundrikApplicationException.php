<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Shared\Application\Exceptions;

use RuntimeException;

/**
 * Signals a failure in the application layer.
 *
 * Raised from use case logic such as services, DTO factories, or orchestration code.
 * Used to distinguish application-level issues from domain and infrastructure failures.
 *
 * @since 1.0.0
 */
abstract class FundrikApplicationException extends RuntimeException {}
