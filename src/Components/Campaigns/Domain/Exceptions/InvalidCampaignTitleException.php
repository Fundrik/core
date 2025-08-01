<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Domain\Exceptions;

/**
 * Signals when the title is empty or consists solely of whitespace.
 *
 * @since 1.0.0
 */
final class InvalidCampaignTitleException extends CampaignDomainException {}
