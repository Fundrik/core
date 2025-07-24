<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Domain\Exceptions;

/**
 * Signals when the target amount is inconsistent with the target state.
 *
 * @since 1.0.0
 */
final class InvalidCampaignTargetException extends CampaignDomainException {}
