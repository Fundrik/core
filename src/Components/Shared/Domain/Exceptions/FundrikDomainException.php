<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Shared\Domain\Exceptions;

use DomainException;

/**
 * Signals a failure in the domain layer.
 *
 * Raised when domain invariants, business rules, or validation logic are violated.
 * Used to distinguish domain-specific errors from application and infrastructure concerns.
 *
 * @since 1.0.0
 */
abstract class FundrikDomainException extends DomainException {}
