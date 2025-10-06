<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Fixtures;

use Exception;
use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositoryExceptionInterface;

final class FakeCampaignRepositoryException extends Exception implements CampaignRepositoryExceptionInterface {}
