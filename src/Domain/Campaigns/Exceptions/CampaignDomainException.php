<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain\Campaigns\Exceptions;

use Fundrik\Core\Domain\Exceptions\FundrikDomainException;

/**
 * Base exception class for all domain-specific errors related to Campaign entities.
 *
 * This exception serves as a common ancestor for more specific Campaign-related exceptions,
 * allowing callers to catch all campaign domain errors in a single catch block if needed.
 *
 * @since 1.0.0
 */
abstract class CampaignDomainException extends FundrikDomainException {}
