<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain\Campaigns\Exceptions;

use InvalidArgumentException;

/**
 * Thrown when a campaign target configuration is invalid.
 *
 * This exception is used when the target amount is inconsistent with
 * the enabled/disabled flag — for example, when a positive amount is
 * set while targeting is disabled, or a zero/negative amount is set
 * while targeting is enabled.
 *
 * @since 1.0.0
 */
final class InvalidCampaignTargetException extends InvalidArgumentException {}
