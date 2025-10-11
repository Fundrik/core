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
use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositoryExceptionInterface;
use Fundrik\Core\Components\Campaigns\Application\Services\CampaignCommandService;
use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTarget;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTitle;
use Fundrik\Core\Components\Shared\Domain\EntityId;
use Fundrik\Core\Examples\Infrastructure\EchoLogger;
use Fundrik\Core\Examples\Infrastructure\InMemoryCampaignRepository;
use Fundrik\Core\Examples\Infrastructure\LoggerEventBus;

require __DIR__ . '/../../vendor/autoload.php';

final readonly class CommandServiceFailures {

	private CampaignCommandService $service;

	public function __construct() {

		$this->wire_dependencies();
	}

	private function wire_dependencies(): void {

		$psr_logger = new EchoLogger();
		$logger = new CampaignCommandServiceLogger( $psr_logger );
		$dto_factory = new CampaignDtoFactory();

		$repo = new InMemoryCampaignRepository( $dto_factory );
		$ok_bus = new LoggerEventBus( $psr_logger );

		$this->service = new CampaignCommandService( $repo, $logger, $ok_bus );
	}

	public function run(): void {

		echo "\n=== create_campaign duplicate ID (expect repository exception) ===\n";
		$this->scenario_duplicate_insert();

		echo "\n=== update_campaign missing ID (expect repository exception) ===\n";
		$this->scenario_update_missing();

		echo "\n=== delete_campaign missing ID (expect repository exception) ===\n";
		$this->scenario_delete_missing();

		echo "\nDone.\n";
	}

	private function scenario_duplicate_insert(): void {

		$id = EntityId::create( 100 );

		$one = new Campaign(
			id: $id,
			title: CampaignTitle::create( 'Alpha' ),
			is_active: true,
			is_open: true,
			target: CampaignTarget::create( false, 0 ),
		);

		$this->service->create_campaign( $one );
		echo "First create OK (ID={$id->get_value()})\n";

		$conflict = new Campaign(
			id: $id,
			title: CampaignTitle::create( 'Alpha (duplicate)' ),
			is_active: true,
			is_open: true,
			target: CampaignTarget::create( true, 500 ),
		);

		try {

			$this->service->create_campaign( $conflict );
			echo "UNEXPECTED: duplicate insert passed\n";
		} catch ( CampaignRepositoryExceptionInterface $e ) {

			echo "Caught repository exception on duplicate insert: {$e->getMessage()}\n";
		}
	}

	private function scenario_update_missing(): void {

		$missing_id = EntityId::create( 999 );

		$ghost = new Campaign(
			id: $missing_id,
			title: CampaignTitle::create( 'Ghost' ),
			is_active: true,
			is_open: true,
			target: CampaignTarget::create( true, 1_000 ),
		);

		try {

			$this->service->update_campaign( $ghost );
			echo "UNEXPECTED: update of missing ID passed\n";
		} catch ( CampaignRepositoryExceptionInterface $e ) {

			echo "Caught repository exception on update: {$e->getMessage()}\n";
		}
	}

	private function scenario_delete_missing(): void {

		$missing_id = EntityId::create( 777 );

		try {

			$this->service->delete_campaign( $missing_id );
			echo "UNEXPECTED: delete of missing ID passed\n";
		} catch ( CampaignRepositoryExceptionInterface $e ) {

			echo "Caught repository exception on delete: {$e->getMessage()}\n";
		}
	}
}

echo '<pre>';
( new CommandServiceFailures() )->run();
echo '</pre>';

// phpcs:enable
