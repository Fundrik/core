<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application\Exceptions;

// phpcs:disable SlevomatCodingStandard.Files.LineLength.LineTooLong
/**
 * Thrown when the CampaignDtoFactory fails to create a DTO from input data.
 *
 * @since 0.1.0
 */
final class CampaignDtoFactoryException extends CampaignApplicationException implements CampaignDtoFactoryExceptionInterface {}
// phpcs:enable
