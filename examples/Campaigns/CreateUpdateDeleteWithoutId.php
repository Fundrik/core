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
use Fundrik\Core\Components\Campaigns\Domain\CampaignTarget;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTitle;
use Fundrik\Core\Examples\Infrastructure\EchoLogger;
use Fundrik\Core\Examples\Infrastructure\InMemoryCampaignRepository;
use Fundrik\Core\Examples\Infrastructure\LoggerEventBus;

require __DIR__ . '/../../vendor/autoload.php';

final readonly class CreateUpdateDeleteWithoutId {

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

		echo "\n=== create_campaign_without_id → update → delete ===\n";

		$created = $this->command_service->create_campaign_without_id(
			title: CampaignTitle::create( 'Clean Water for Kids' ),
			is_active: true,
			is_open: true,
			target: CampaignTarget::create( true, 500 ),
		);

		$id = $created->get_entity_id();
		echo "Created: ID={$id->get_value()}, Title={$created->get_title()}, Target={$created->get_target_amount()}\n";

		$updated = $created
			->rename( 'Clean Water for Kids — Phase 2' )
			->set_target_amount( 750 );

		$this->command_service->update_campaign( $updated );
		echo "Updated: ID={$id->get_value()}, Title={$updated->get_title()}, Target={$updated->get_target_amount()}\n";

		$this->command_service->delete_campaign( $id );
		echo "Deleted: ID={$id->get_value()}\n";

		echo "\nDone.";
	}
}

echo '<pre>';
( new CreateUpdateDeleteWithoutId() )->run();
echo '</pre>';

// phpcs:enable
