<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain\Exceptions;

use DomainException;

// phpcs:disable SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.MultipleEmptyLinesAfterOpeningBrace, SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.IncorrectEmptyLinesBeforeClosingBrace
/**
 * Base exception for domain-related errors in the Fundrik core.
 *
 * This exception serves as a common ancestor for all exceptions
 * related to domain invariants, rules, and validation logic.
 *
 * Catching this exception allows handling any domain-level failure
 * across subdomains (e.g., Campaigns, Donations) in a consistent way.
 *
 * @since 1.0.0
 */
abstract class FundrikDomainException extends DomainException {}
// phpcs:enable SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.MultipleEmptyLinesAfterOpeningBrace, SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces.IncorrectEmptyLinesBeforeClosingBrace