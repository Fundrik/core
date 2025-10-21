<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application\Loggers;

use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositoryExceptionInterface;
use Fundrik\Core\Components\Campaigns\Application\Services\CampaignCommandService;
use LogicException;
use Throwable;

/**
 * Logs application-level operations of the CampaignCommandService.
 *
 * @since 0.1.0
 */
final readonly class CampaignCommandServiceLogger extends AbstractCampaignServiceLogger {

	private const OPERATION_SAVE = 'save_campaign';
	private const OPERATION_DELETE = 'delete_campaign';

	/**
	 * Logs the start of a save operation (debug).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID being saved.
	 * @param CampaignSaveLogAction $action The type of save performed.
	 *
	 * @codeCoverageIgnore
	 */
	public function log_save_started( int|string $id, CampaignSaveLogAction $action ): void {

		$this->logger->debug(
			'Saving campaign started.',
			$this->logger_context(
				[
					'operation' => self::OPERATION_SAVE,
					'id' => $id,
					'action' => $action->value,
				],
			),
		);
	}

	/**
	 * Logs repository failure during a save operation (error).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID being saved.
	 * @param CampaignRepositoryExceptionInterface $e The repository exception that occurred.
	 * @param CampaignSaveLogAction $action The type of save performed.
	 */
	public function log_save_failed_repository(
		int|string $id,
		CampaignRepositoryExceptionInterface $e,
		CampaignSaveLogAction $action,
	): void {

		$this->logger->error(
			'Saving campaign failed (repository error).',
			$this->logger_context(
				[
					'operation' => self::OPERATION_SAVE,
					'id' => $id,
					'exception' => $e,
					'exception_class' => $e::class,
					'action' => $action->value,
				],
			),
		);
	}

	// phpcs:disable SlevomatCodingStandard.Files.LineLength.LineTooLong

	/**
	 * Logs a warning when publishing CampaignCreatedEvent/CampaignUpdatedEvent fails (warning).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID related to the failed publish.
	 * @param Throwable $e The exception thrown by a listener or the event bus.
	 * @param CampaignSaveLogAction $action The type of save performed.
	 *
	 * @throws LogicException Cannot publish saved event: action must be Create or Update.
	 */
	public function log_publish_saved_event_failed( int|string $id, Throwable $e, CampaignSaveLogAction $action ): void {

		$event_label = match ( $action ) {
			CampaignSaveLogAction::Create => 'CampaignCreatedEvent',
			CampaignSaveLogAction::Update => 'CampaignUpdatedEvent',
			CampaignSaveLogAction::Save => throw new LogicException(
				'Cannot publish saved event: action must be Create or Update.',
			),
		};

		$this->logger->warning(
			sprintf( 'Publishing %s failed (event bus error).', $event_label ),
			$this->logger_context(
				[
					'operation' => self::OPERATION_SAVE,
					'id' => $id,
					'exception' => $e,
					'exception_class' => $e::class,
					'action' => $action->value,
				],
			),
		);
	}
	// phpcs:enable

	/**
	 * Logs successful completion of a save operation (info).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID saved.
	 * @param CampaignSaveLogAction $action The type of save performed.
	 */
	public function log_save_succeeded( int|string $id, CampaignSaveLogAction $action ): void {

		$this->logger->info(
			'Saving campaign succeeded.',
			$this->logger_context(
				[
					'operation' => self::OPERATION_SAVE,
					'id' => $id,
					'action' => $action->value,
				],
			),
		);
	}

	/**
	 * Logs the start of a delete operation (debug).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID being deleted.
	 *
	 * @codeCoverageIgnore
	 */
	public function log_delete_started( int|string $id ): void {

		$this->logger->debug(
			'Deleting campaign started.',
			$this->logger_context(
				[
					'operation' => self::OPERATION_DELETE,
					'id' => $id,
				],
			),
		);
	}

	/**
	 * Logs repository failure during a delete operation (error).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID being deleted.
	 * @param CampaignRepositoryExceptionInterface $e The repository exception that occurred.
	 */
	public function log_delete_failed_repository( int|string $id, CampaignRepositoryExceptionInterface $e ): void {

		$this->logger->error(
			'Deleting campaign failed (repository error).',
			$this->logger_context(
				[
					'operation' => self::OPERATION_DELETE,
					'id' => $id,
					'exception' => $e,
					'exception_class' => $e::class,
				],
			),
		);
	}

	/**
	 * Logs a warning when publishing CampaignDeletedEvent fails (warning).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID related to the failed publish.
	 * @param Throwable $e The exception thrown by a listener or the event bus.
	 */
	public function log_publish_deleted_event_failed( int|string $id, Throwable $e ): void {

		$this->logger->warning(
			'Publishing CampaignDeletedEvent failed (event bus error).',
			$this->logger_context(
				[
					'operation' => self::OPERATION_DELETE,
					'id' => $id,
					'exception' => $e,
					'exception_class' => $e::class,
				],
			),
		);
	}

	/**
	 * Logs successful completion of a delete operation (info).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID deleted.
	 */
	public function log_delete_succeeded( int|string $id ): void {

		$this->logger->info(
			'Deleting campaign succeeded.',
			$this->logger_context(
				[
					'operation' => self::OPERATION_DELETE,
					'id' => $id,
				],
			),
		);
	}

	/**
	 * Returns the class name of the subject being logged.
	 *
	 * @since 0.1.0
	 *
	 * @return string The fully qualified class name of the subject service to attribute the log entries to.
	 *
	 * @phpstan-return class-string
	 */
	protected function subject_class(): string {

		return CampaignCommandService::class;
	}

	/**
	 * Provides platform-/runtime-specific context fields.
	 *
	 * @since 0.1.0
	 *
	 * @return array<string, mixed> The platform-specific context entries.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
	 */
	protected function platform_context(): array {

		return [ 'system' => 'core' ];
	}
}
