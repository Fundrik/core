<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application\Exceptions;

// phpcs:disable SlevomatCodingStandard.Files.LineLength.LineTooLong
/**
 * Thrown when the CampaignAssembler fails.
 *
 * @since 0.1.0
 */
final class CampaignAssemblerException extends CampaignApplicationException implements CampaignAssemblerExceptionInterface {}
// phpcs:enable