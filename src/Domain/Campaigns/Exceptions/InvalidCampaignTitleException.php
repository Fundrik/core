<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain\Campaigns\Exceptions;

use InvalidArgumentException;

/**
 * Thrown when a campaign title is invalid.
 *
 * This exception is used when the campaign title is empty or consists only of whitespace.
 *
 * @since 1.0.0
 */
final class InvalidCampaignTitleException extends InvalidArgumentException {}
