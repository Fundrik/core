<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain\Campaigns\Exceptions;

/**
 * Exception thrown when a campaign target configuration is invalid.
 *
 * Typically thrown by CampaignTarget::create when target is enabled but amount is zero or negative.
 *
 * @since 1.0.0
 */
final class InvalidCampaignTargetException extends CampaignDomainException {}
