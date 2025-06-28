<?php

declare(strict_types=1);

namespace Fundrik\Core\Application\Campaigns\Exceptions;

use RuntimeException;

// phpcs:disable SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.MultipleEmptyLinesAfterOpeningBrace, SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.IncorrectEmptyLinesBeforeClosingBrace
/**
 * Exception thrown when the input data provided to the Campaign DTO factory
 * is invalid or cannot be properly converted into a Campaign DTO.
 *
 * This typically indicates that required fields are missing or contain
 * invalid values that violate the expected data format or constraints.
 *
 * @since 1.0.0
 */
final class InvalidCampaignDtoInputException extends RuntimeException {}
// phpcs:enable SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.MultipleEmptyLinesAfterOpeningBrace, SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.IncorrectEmptyLinesBeforeClosingBrace