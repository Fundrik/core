<?php

declare(strict_types=1);

namespace Fundrik\Core\Application\Campaigns\Exceptions;

use Fundrik\Core\Application\Exceptions\FundrikApplicationException;

/**
 * Base exception class for all application-specific errors related to Campaign use cases.
 *
 * This exception is the parent for all Campaign-related failures in the application layer,
 * such as DTO construction, service orchestration, or data transformation issues.
 *
 * @since 1.0.0
 */
abstract class CampaignApplicationException extends FundrikApplicationException {}
