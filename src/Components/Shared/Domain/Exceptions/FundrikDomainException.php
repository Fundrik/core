<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Shared\Domain\Exceptions;

use DomainException;

/**
 * Serves as the base exception for domain-layer errors.
 *
 * @since 0.1.0
 */
abstract class FundrikDomainException extends DomainException {}
