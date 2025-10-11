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
use Ramsey\Uuid\Uuid;

require __DIR__ . '/../../vendor/autoload.php';

final readonly class CreateUpdateDeleteWithId {

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

		echo "\n=== create_campaign (with int ID) ===\n";
		$this->scenario_with_int_id();

		echo "\n=== create_campaign (with uuid ID) ===\n";
		$this->scenario_with_uuid_id();

		echo "\nDone.";
	}

	private function scenario_with_int_id(): void {

		$id = EntityId::create( 100 );

		$campaign = new Campaign(
			id: $id,
			title: CampaignTitle::create( 'Books for Rural Schools' ),
			is_active: true,
			is_open: true,
			target: CampaignTarget::create( false, 0 ),
		);

		$this->command_service->create_campaign( $campaign );
		echo "Created: ID={$id->get_value()}, Title={$campaign->get_title()}\n";

		$updated = new Campaign(
			id: $id,
			title: CampaignTitle::create( 'Books for Rural Schools — Extended' ),
			is_active: true,
			is_open: false,
			target: CampaignTarget::create( true, 2_500 ),
		);

		$this->command_service->update_campaign( $updated );
		echo "Updated: ID={$id->get_value()}, Title={$updated->get_title()}, Target={$updated->get_target_amount()}\n";

		$this->command_service->delete_campaign( $id );
		echo "Deleted: ID={$id->get_value()}\n";
	}

	private function scenario_with_uuid_id(): void {

		$id = EntityId::create( Uuid::uuid7()->toString() );

		$campaign = new Campaign(
			id: $id,
			title: CampaignTitle::create( 'Clean Energy for Schools' ),
			is_active: true,
			is_open: true,
			target: CampaignTarget::create( true, 10_000 ),
		);

		$this->command_service->create_campaign( $campaign );
		echo "Created: ID={$id->get_value()}, Title={$campaign->get_title()}\n";

		$updated = new Campaign(
			id: $id,
			title: CampaignTitle::create( 'Clean Energy for Schools — Pilot Phase' ),
			is_active: false,
			is_open: false,
			target: CampaignTarget::create( true, 12_000 ),
		);

		$this->command_service->update_campaign( $updated );
		echo "Updated: ID={$id->get_value()}, Title={$updated->get_title()}, Target={$updated->get_target_amount()}\n";

		$this->command_service->delete_campaign( $id );
		echo "Deleted: ID={$id->get_value()}\n";
	}
}

echo '<pre>';
( new CreateUpdateDeleteWithId() )->run();
echo '</pre>';

// phpcs:enable
