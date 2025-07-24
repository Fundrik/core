<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Domain\Exceptions;

use Fundrik\Core\Components\Shared\Domain\Exceptions\FundrikDomainException;

/**
 * Signals a domain-level error related to campaign rules or invariants.
 *
 * Serves as the base exception for all campaign-specific domain errors,
 * allowing unified exception handling for campaign-related failures.
 *
 * @since 1.0.0
 */
abstract class CampaignDomainException extends FundrikDomainException {}
