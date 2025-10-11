<?php

declare(strict_types=1);

namespace Fundrik\Core\Examples\Campaigns;

// phpcs:disable FundrikStandard.Commenting.SinceTagRequired.MissingSince
// phpcs:disable Squiz.Commenting.ClassComment.Missing
// phpcs:disable Squiz.Commenting.FunctionComment.Missing
// phpcs:disable Squiz.Commenting.VariableComment.Missing
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
// phpcs:disable SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh

use Fundrik\Core\Components\Campaigns\Application\CampaignAssembler;
use Fundrik\Core\Components\Campaigns\Application\CampaignDtoFactory;
use Fundrik\Core\Components\Campaigns\Application\Loggers\CampaignCommandServiceLogger;
use Fundrik\Core\Components\Campaigns\Application\Loggers\CampaignQueryServiceLogger;
use Fundrik\Core\Components\Campaigns\Application\Services\CampaignCommandService;
use Fundrik\Core\Components\Campaigns\Application\Services\CampaignQueryService;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTarget;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTitle;
use Fundrik\Core\Examples\Infrastructure\EchoLogger;
use Fundrik\Core\Examples\Infrastructure\InMemoryCampaignRepository;
use Fundrik\Core\Examples\Infrastructure\LoggerEventBus;

require __DIR__ . '/../../vendor/autoload.php';

final readonly class FindAllCampaigns {

	private CampaignQueryService $query_service;
	private CampaignCommandService $command_service;

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
		$cmd_logger = new CampaignCommandServiceLogger( $psr_logger );
		$event_bus = new LoggerEventBus( $psr_logger );

		$this->query_service = new CampaignQueryService( $assembler, $repository, $query_logger );
		$this->command_service = new CampaignCommandService( $repository, $cmd_logger, $event_bus );
	}

	private function seed_dummy_data(): void {

		echo "\n=== seed_dummy_data ===\n";

		// 1) Active & open, with target
		$this->command_service->create_campaign_without_id(
			title: CampaignTitle::create( 'Clean Water for Kids' ),
			is_active: true,
			is_open: true,
			target: CampaignTarget::create( true, 500 ),
		);

		// 2) Active & open, without target
		$this->command_service->create_campaign_without_id(
			title: CampaignTitle::create( 'Books for Rural Schools' ),
			is_active: true,
			is_open: true,
			target: CampaignTarget::create( false, 0 ),
		);

		// 3) Inactive & closed, with target
		$this->command_service->create_campaign_without_id(
			title: CampaignTitle::create( 'Community Health Van' ),
			is_active: false,
			is_open: false,
			target: CampaignTarget::create( true, 2_500 ),
		);
	}

	public function run(): void {

		$this->scenario_list_all();

		echo "\nDone.";
	}

	private function scenario_list_all(): void {

		echo "\n=== find_all_campaigns ===\n";

		$all = $this->query_service->find_all_campaigns();

		echo 'Total: ' . count( $all ) . "\n";

		foreach ( $all as $i => $campaign ) {

			$idx = $i + 1;
			$id = $campaign->get_id();
			$title = $campaign->get_title();
			$active = $campaign->is_active() ? 'yes' : 'no';
			$open = $campaign->is_open() ? 'yes' : 'no';
			$target = $campaign->has_target() ? (string) $campaign->get_target_amount() : 'no';

			echo "#{$idx}: ID={$id}, Title={$title}, Active={$active}, Open={$open}, Target={$target}\n";
		}
	}
}

echo '<pre>';
( new FindAllCampaigns() )->run();
echo '</pre>';

// phpcs:enable