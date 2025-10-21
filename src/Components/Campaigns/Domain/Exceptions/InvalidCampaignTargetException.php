<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Domain\Exceptions;

/**
 * Thrown when the campaign target amount is inconsistent with the target state.
 *
 * @since 0.1.0
 */
final class InvalidCampaignTargetException extends CampaignDomainException {}
