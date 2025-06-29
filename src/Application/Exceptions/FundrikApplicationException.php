<?php

declare(strict_types=1);

namespace Fundrik\Core\Application\Exceptions;

use RuntimeException;

/**
 * Base exception for all application-layer errors in the Fundrik core.
 *
 * This abstract class serves as the root of the exception hierarchy for
 * the application logic (use cases, DTO factories, services, etc.).
 *
 * Catching this exception allows consistent handling of all core application
 * failures, distinct from domain and infrastructure errors.
 *
 * @since 1.0.0
 */
abstract class FundrikApplicationException extends RuntimeException {}
