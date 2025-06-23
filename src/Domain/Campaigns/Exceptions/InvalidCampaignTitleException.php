<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain\Campaigns\Exceptions;

use InvalidArgumentException;

// phpcs:disable SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.MultipleEmptyLinesAfterOpeningBrace, SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.IncorrectEmptyLinesBeforeClosingBrace
/**
 * Exception thrown when a campaign title is invalid.
 *
 * Typically thrown by CampaignTitle::create when the value is empty or contains only whitespace.
 *
 * @since 1.0.0
 */
final class InvalidCampaignTitleException extends InvalidArgumentException {}
// phpcs:enable SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.MultipleEmptyLinesAfterOpeningBrace, SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.IncorrectEmptyLinesBeforeClosingBrace