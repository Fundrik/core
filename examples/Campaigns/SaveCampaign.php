<?php

declare(strict_types=1);

namespace Fundrik\Core\Examples\Campaigns;

// phpcs:disable FundrikStandard.Commenting.SinceTagRequired.MissingSince
// phpcs:disable Squiz.Commenting.ClassComment.Missing
// phpcs:disable Squiz.Commenting.FunctionComment.Missing
// phpcs:disable Squiz.Commenting.VariableComment.Missing
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
// phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength

use Fundrik\Core\Components\Campaigns\Application\CampaignDtoFactory;
use Fundrik\Core\Components\Campaigns\Application\Loggers\CampaignCommandServiceLogger;
use Fundrik\Core\Components\Campaigns\Application\Services\CampaignCommandService;
use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTarget;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTitle;
use Fundrik\Core\Components\Shared\Domain\EntityId;
use Fundrik\Core\Examples\Infrastructure\EchoLogger;
use Fundrik\Core\Examples\Infrastructure\InMemoryCampaignRepository;
use Fundrik\Core\Examples\Infrastructure\LoggerEventBus;

require __DIR__ . '/../../vendor/autoload.php';

final readonly class SaveCampaign {

	private CampaignCommandService $command_service;

	public function __construct() {

		$this->wire_dependencies();
	}

	private function wire_dependencies(): void {

		$psr_logger = new EchoLogger();
		$dto_factory = new CampaignDtoFactory();
		$repository = new InMemoryCampaignRepository( $dto_factory );
		$logger = new CampaignCommandServiceLogger( $psr_logger );
		$event_bus = new LoggerEventBus( $psr_logger );

		$this->command_service = new CampaignCommandService( $repository, $logger, $event_bus );
	}

	public function run(): void {

		echo "\n=== save_campaign (Inserted → Updated) ===\n";

		$id = EntityId::create( 200 );

		$first = new Campaign(
			id: $id,
			title: CampaignTitle::create( 'Community Health Van' ),
			is_active: true,
			is_open: true,
			target: CampaignTarget::create( true, 10_000 ),
		);

		$this->command_service->save_campaign( $first );
		echo "save_campaign #1: Inserted (ID={$id->get_value()}, Target={$first->get_target_amount()})\n";

		$second = new Campaign(
			id: $id,
			title: CampaignTitle::create( 'Community Health Van — Stage 2' ),
			is_active: true,
			is_open: true,
			target: CampaignTarget::create( true, 12_500 ),
		);

		$this->command_service->save_campaign( $second );
		echo "save_campaign #2: Updated (ID={$id->get_value()}, Target={$second->get_target_amount()})\n";

		echo "\nDone.";
	}
}

echo '<pre>';
( new SaveCampaign() )->run();
echo '</pre>';

// phpcs:enable
