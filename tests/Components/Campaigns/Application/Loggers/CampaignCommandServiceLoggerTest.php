<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Components\Campaigns\Application\Loggers;

use Fundrik\Core\Components\Campaigns\Application\Loggers\CampaignCommandServiceLogger;
use Fundrik\Core\Components\Campaigns\Application\Loggers\CampaignSaveLogAction;
use Fundrik\Core\Components\Campaigns\Application\Services\CampaignCommandService;
use Fundrik\Core\Tests\Fixtures\FakeCampaignRepositoryException;
use Fundrik\Core\Tests\MockeryTestCase;
use LogicException;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Log\LoggerInterface;
use RuntimeException;

#[CoversClass( CampaignCommandServiceLogger::class )]
final class CampaignCommandServiceLoggerTest extends MockeryTestCase {

	private LoggerInterface&MockInterface $psr_logger;
	private CampaignCommandServiceLogger $logger;

	protected function setUp(): void {

		parent::setUp();

		$this->psr_logger = Mockery::mock( LoggerInterface::class );
		$this->logger = new CampaignCommandServiceLogger( $this->psr_logger );
	}

	#[Test]
	public function log_save_failed_repository_writes_error_with_exception(): void {

		$e = new FakeCampaignRepositoryException();
		$action = CampaignSaveLogAction::Update;

		$this->psr_logger
			->shouldReceive( 'error' )
			->once()
			->with(
				'Saving campaign failed (repository error).',
				$this->log_context(
					[
						'operation' => 'save_campaign',
						'id' => 7,
						'exception' => $e,
						'exception_class' => $e::class,
						'action' => $action->value,
					],
				),
			);

		$this->logger->log_save_failed_repository( id: 7, e: $e, action: $action );
	}

	#[Test]
	public function log_publish_saved_event_failed_writes_warning_for_create_action(): void {

		$e = new RuntimeException();
		$action = CampaignSaveLogAction::Create;

		$this->psr_logger
			->shouldReceive( 'warning' )
			->once()
			->with(
				'Publishing CampaignCreatedEvent failed (event bus error).',
				$this->log_context(
					[
						'operation' => 'save_campaign',
						'id' => 123,
						'exception' => $e,
						'exception_class' => $e::class,
						'action' => $action->value,
					],
				),
			);

		$this->logger->log_publish_saved_event_failed( id: 123, e: $e, action: $action );
	}

	#[Test]
	public function log_publish_saved_event_failed_writes_warning_for_update_action(): void {

		$e = new RuntimeException();
		$action = CampaignSaveLogAction::Update;

		$this->psr_logger
			->shouldReceive( 'warning' )
			->once()
			->with(
				'Publishing CampaignUpdatedEvent failed (event bus error).',
				$this->log_context(
					[
						'operation' => 'save_campaign',
						'id' => 456,
						'exception' => $e,
						'exception_class' => $e::class,
						'action' => $action->value,
					],
				),
			);

		$this->logger->log_publish_saved_event_failed( id: 456, e: $e, action: $action );
	}

	#[Test]
	public function log_publish_saved_event_failed_throws_on_save_action(): void {

		$this->expectException( LogicException::class );
		$this->expectExceptionMessage( 'Cannot publish saved event: action must be Create or Update.' );

		$this->psr_logger->shouldNotReceive( 'warning' );

		$this->logger->log_publish_saved_event_failed(
			id: 999,
			e: new RuntimeException(),
			action: CampaignSaveLogAction::Save,
		);
	}

	#[Test]
	public function log_save_succeeded_writes_info_with_required_context(): void {

		$action = CampaignSaveLogAction::Update;

		$this->psr_logger
			->shouldReceive( 'info' )
			->once()
			->with(
				'Saving campaign succeeded.',
				$this->log_context(
					[
						'operation' => 'save_campaign',
						'id' => 123,
						'action' => $action->value,
					],
				),
			);

		$this->logger->log_save_succeeded( id: 123, action: $action );
	}

	#[Test]
	public function log_delete_failed_repository_writes_error_with_exception(): void {

		$e = new FakeCampaignRepositoryException();

		$this->psr_logger
			->shouldReceive( 'error' )
			->once()
			->with(
				'Deleting campaign failed (repository error).',
				$this->log_context(
					[
						'operation' => 'delete_campaign',
						'id' => 7,
						'exception' => $e,
						'exception_class' => $e::class,
					],
				),
			);

		$this->logger->log_delete_failed_repository( id: 7, e: $e );
	}

	#[Test]
	public function log_publish_deleted_event_failed_writes_warning_with_exception_and_id(): void {

		$e = new RuntimeException();

		$this->psr_logger
			->shouldReceive( 'warning' )
			->once()
			->with(
				'Publishing CampaignDeletedEvent failed (event bus error).',
				$this->log_context(
					[
						'operation' => 'delete_campaign',
						'id' => 42,
						'exception' => $e,
						'exception_class' => $e::class,
					],
				),
			);

		$this->logger->log_publish_deleted_event_failed( id: 42, e: $e );
	}

	#[Test]
	public function log_delete_succeeded_writes_info_with_required_context(): void {

		$this->psr_logger
			->shouldReceive( 'info' )
			->once()
			->with(
				'Deleting campaign succeeded.',
				$this->log_context(
					[
						'operation' => 'delete_campaign',
						'id' => 42,
					],
				),
			);

		$this->logger->log_delete_succeeded( id: 42 );
	}

	private function log_context( array $expected ): \Mockery\Matcher\Closure {

		return $this->array_has(
			$expected + [
				'service_class' => CampaignCommandService::class,
				'logger_class' => CampaignCommandServiceLogger::class,
				'component' => 'campaigns',
				'layer' => 'application',
				'system' => 'core',
			],
		);
	}
}
