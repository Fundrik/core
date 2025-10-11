<?php

declare(strict_types=1);

namespace Fundrik\Core\Examples\Campaigns;

// phpcs:disable FundrikStandard.Commenting.SinceTagRequired.MissingSince
// phpcs:disable Squiz.Commenting.ClassComment.Missing
// phpcs:disable Squiz.Commenting.FunctionComment.Missing
// phpcs:disable Squiz.Commenting.VariableComment.Missing
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped

use Fundrik\Core\Components\Campaigns\Application\CampaignAssembler;
use Fundrik\Core\Components\Campaigns\Application\CampaignDtoFactory;
use Fundrik\Core\Components\Campaigns\Application\Loggers\CampaignCommandServiceLogger;
use Fundrik\Core\Components\Campaigns\Application\Loggers\CampaignQueryServiceLogger;
use Fundrik\Core\Components\Campaigns\Application\Services\CampaignCommandService;
use Fundrik\Core\Components\Campaigns\Application\Services\CampaignQueryService;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTarget;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTitle;
use Fundrik\Core\Components\Shared\Domain\EntityId;
use Fundrik\Core\Examples\Infrastructure\EchoLogger;
use Fundrik\Core\Examples\Infrastructure\InMemoryCampaignRepository;
use Fundrik\Core\Examples\Infrastructure\LoggerEventBus;

require __DIR__ . '/../../vendor/autoload.php';

final readonly class FindCampaignById {

	private CampaignQueryService $query_service;
	private CampaignCommandService $command_service;

	private EntityId $existing_id;

	public function __construct() {

		$this->wire_dependencies();
		$this->seed_dummy_data();
	}

	private function wire_dependencies(): void {

		$psr_logger = new EchoLogger();
		$dto_factory = new CampaignDtoFactory();

		$assembler = new CampaignAssembler();
		$repository = new InMemoryCampaignRepository( $dto_factory );
		$query_logger = new CampaignQueryServiceLogger( $psr_logger );
		$command_logger = new CampaignCommandServiceLogger( $psr_logger );
		$event_bus = new LoggerEventBus( $psr_logger );

		$this->query_service = new CampaignQueryService( $assembler, $repository, $query_logger );
		$this->command_service = new CampaignCommandService( $repository, $command_logger, $event_bus );
	}

	private function seed_dummy_data(): void {

		echo "\n=== seed_dummy_data ===\n";

		$title = CampaignTitle::create( 'Clean Water for Kids' );
		$target = CampaignTarget::create( true, 500 );

		$created = $this->command_service->create_campaign_without_id(
			title: $title,
			is_active: true,
			is_open: true,
			target: $target,
		);

		$this->existing_id = $created->get_entity_id();
	}

	public function run(): void {

		$this->scenario_existing_id();
		$this->scenario_missing_id();

		echo "\nDone.";
	}

	private function scenario_existing_id(): void {

		echo "\n=== find_campaign_by_id (existing) ===\n";

		$found = $this->query_service->find_campaign_by_id( $this->existing_id );

		if ( $found !== null ) {
			echo "Found: ID={$found->get_id()}, Title={$found->get_title()}, Target=" .
				( $found->has_target() ? $found->get_target_amount() : 'no' ) . "\n";
		} else {
			echo "Not found (unexpected)\n";
		}
	}

	private function scenario_missing_id(): void {

		echo "\n=== find_campaign_by_id (missing) ===\n";

		$missing_id = EntityId::create( 9_999 );
		$missing = $this->query_service->find_campaign_by_id( $missing_id );

		echo $missing === null ? "Not found (as expected)\n" : "Found (unexpected)\n";
	}
}

echo '<pre>';
( new FindCampaignById() )->run();
echo '</pre>';

// phpcs:enable