<?php

declare(strict_types=1);

// phpcs:disable FundrikStandard.Commenting.SinceTagRequired.MissingSince
// phpcs:disable Squiz.Commenting.ClassComment.Missing
// phpcs:disable Squiz.Commenting.FunctionComment.Missing

namespace Fundrik\Core\Examples\Infrastructure;

use Fundrik\Core\Components\Shared\Application\Ports\Out\EventBusPort;
use Psr\Log\LoggerInterface;

final readonly class LoggerEventBus implements EventBusPort {

	public function __construct(
		private LoggerInterface $logger,
	) {}

	public function publish( object $event ): void {

		$event_name = $event::class;

		$this->logger->info(
			'Publishing event',
			[
				'event_name' => $event_name,
				'event' => $event,
			],
		);
	}
}

// phpcs:enable