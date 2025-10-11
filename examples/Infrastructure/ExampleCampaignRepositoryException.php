<?php

declare(strict_types=1);

// phpcs:disable FundrikStandard.Commenting.SinceTagRequired.MissingSince
// phpcs:disable Squiz.Commenting.ClassComment.Missing
// phpcs:disable SlevomatCodingStandard.Files.LineLength.LineTooLong

namespace Fundrik\Core\Examples\Infrastructure;

use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositoryExceptionInterface;
use RuntimeException;

final class ExampleCampaignRepositoryException extends RuntimeException implements CampaignRepositoryExceptionInterface {}

// phpcs:enable