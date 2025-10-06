<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application\Loggers;

use Fundrik\Core\Components\Campaigns\Application\Exceptions\CampaignAssemblerExceptionInterface;
use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositoryExceptionInterface;
use LogicException;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Provides structured, platform-agnostic logging for CampaignService operations.
 *
 * @since 0.1.0
 */
abstract readonly class AbstractCampaignServiceLogger {

	private const OPERATION_FIND_BY_ID = 'find_campaign_by_id';
	private const OPERATION_FIND_ALL = 'find_all_campaigns';
	private const OPERATION_SAVE = 'save_campaign';
	private const OPERATION_DELETE = 'delete_campaign';

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param LoggerInterface $logger Delegates logging to the underlying PSR-3 logger.
	 */
	public function __construct(
		protected LoggerInterface $logger,
	) {}

	/**
	 * Logs the start of a find-by-ID operation (debug).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID being searched.
	 *
	 * @codeCoverageIgnore
	 */
	final public function log_find_by_id_started( int|string $id ): void {

		$this->logger->debug(
			'Finding campaign by ID started.',
			$this->logger_context(
				[
					'operation' => self::OPERATION_FIND_BY_ID,
					'id' => $id,
				],
			),
		);
	}

	/**
	 * Logs repository failure during a find-by-ID operation (error).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID being searched.
	 * @param CampaignRepositoryExceptionInterface $e The repository exception that occurred.
	 */
	final public function log_find_by_id_failed_repository(
		int|string $id,
		CampaignRepositoryExceptionInterface $e,
	): void {

		$this->logger->error(
			'Finding campaign by ID failed (repository error).',
			$this->logger_context(
				[
					'operation' => self::OPERATION_FIND_BY_ID,
					'id' => $id,
					'exception' => $e,
					'exception_class' => $e::class,
				],
			),
		);
	}

	/**
	 * Logs that the requested campaign was not found (debug).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID that was not found.
	 *
	 * @codeCoverageIgnore
	 */
	final public function log_find_by_id_not_found( int|string $id ): void {

		$this->logger->debug(
			'Finding campaign by ID not found.',
			$this->logger_context(
				[
					'operation' => self::OPERATION_FIND_BY_ID,
					'id' => $id,
				],
			),
		);
	}

	// phpcs:disable SlevomatCodingStandard.Files.LineLength.LineTooLong
	/**
	 * Logs assembler failure during a find-by-ID operation (error).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID being processed.
	 * @param CampaignAssemblerExceptionInterface $e The assembler exception that occurred.
	 */
	final public function log_find_by_id_failed_assembler( int|string $id, CampaignAssemblerExceptionInterface $e ): void {

		$this->logger->error(
			'Finding campaign by ID failed (assembler error).',
			$this->logger_context(
				[
					'operation' => self::OPERATION_FIND_BY_ID,
					'id' => $id,
					'exception' => $e,
					'exception_class' => $e::class,
				],
			),
		);
	}
	// phpcs:enable

	/**
	 * Logs successful completion of a find-by-ID operation (debug).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID found.
	 *
	 * @codeCoverageIgnore
	 */
	final public function log_find_by_id_succeeded( int|string $id ): void {

		$this->logger->debug(
			'Finding campaign by ID succeeded.',
			$this->logger_context(
				[
					'operation' => self::OPERATION_FIND_BY_ID,
					'id' => $id,
				],
			),
		);
	}

	/**
	 * Logs the start of a find-all operation (debug).
	 *
	 * @since 0.1.0
	 *
	 * @codeCoverageIgnore
	 */
	final public function log_find_all_started(): void {

		$this->logger->debug(
			'Finding campaigns started.',
			$this->logger_context(
				[
					'operation' => self::OPERATION_FIND_ALL,
				],
			),
		);
	}

	/**
	 * Logs repository failure during a find-all operation (error).
	 *
	 * @since 0.1.0
	 *
	 * @param CampaignRepositoryExceptionInterface $e The repository exception that occurred.
	 */
	final public function log_find_all_failed_repository( CampaignRepositoryExceptionInterface $e ): void {

		$this->logger->error(
			'Finding campaigns failed (repository error).',
			$this->logger_context(
				[
					'operation' => self::OPERATION_FIND_ALL,
					'exception' => $e,
					'exception_class' => $e::class,
				],
			),
		);
	}

	/**
	 * Logs assembler failure during a find-all operation (error).
	 *
	 * @since 0.1.0
	 *
	 * @param CampaignAssemblerExceptionInterface $e The assembler exception that occurred.
	 */
	final public function log_find_all_failed_assembler( CampaignAssemblerExceptionInterface $e ): void {

		$this->logger->error(
			'Finding campaigns failed (assembler error).',
			$this->logger_context(
				[
					'operation' => self::OPERATION_FIND_ALL,
					'exception' => $e,
					'exception_class' => $e::class,
				],
			),
		);
	}

	/**
	 * Logs successful completion of a find-all operation (debug).
	 *
	 * @since 0.1.0
	 *
	 * @param int $count The number of campaigns retrieved.
	 *
	 * @codeCoverageIgnore
	 */
	final public function log_find_all_succeeded( int $count ): void {

		$this->logger->debug(
			'Finding campaigns succeeded.',
			$this->logger_context(
				[
					'operation' => self::OPERATION_FIND_ALL,
					'count' => $count,
				],
			),
		);
	}

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
	final public function log_save_started( int|string $id, CampaignSaveLogAction $action ): void {

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
	final public function log_save_failed_repository(
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

	/**
	 * Logs a warning when publishing CampaignCreatedEvent/CampaignUpdatedEvent fails (warning).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID related to the failed publish.
	 * @param Throwable $e The exception thrown by a listener or the event bus.
	 * @param CampaignSaveLogAction $action The type of save performed.
	 */
	final public function log_publish_saved_event_failed(
		int|string $id,
		Throwable $e,
		CampaignSaveLogAction $action,
	): void {

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

	/**
	 * Logs successful completion of a save operation (info).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID saved.
	 * @param CampaignSaveLogAction $action The type of save performed.
	 */
	final public function log_save_succeeded( int|string $id, CampaignSaveLogAction $action ): void {

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
	final public function log_delete_started( int|string $id ): void {

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

	// phpcs:disable SlevomatCodingStandard.Files.LineLength.LineTooLong
	/**
	 * Logs repository failure during a delete operation (error).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID being deleted.
	 * @param CampaignRepositoryExceptionInterface $e The repository exception that occurred.
	 */
	final public function log_delete_failed_repository( int|string $id, CampaignRepositoryExceptionInterface $e ): void {

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
	// phpcs:enable

	/**
	 * Logs a warning when publishing CampaignDeletedEvent fails (warning).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID related to the failed publish.
	 * @param Throwable $e The exception thrown by a listener or the event bus.
	 */
	final public function log_publish_deleted_event_failed( int|string $id, Throwable $e ): void {

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
	final public function log_delete_succeeded( int|string $id ): void {

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
	 * Builds the structured logger context for this service.
	 *
	 * Combines base service context with platform-specific context and extra entries.
	 *
	 * @since 0.1.0
	 *
	 * @param array<string, mixed> $extra Additional context entries to merge.
	 *
	 * @return array<string, mixed> The structured context payload.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
	 */
	final protected function logger_context( array $extra = [] ): array {

		$base = [
			'service_class' => $this->subject_class(),
			'logger_class' => static::class,
			'component' => 'campaigns',
			'layer' => 'application',
		];

		return $base + $this->platform_context() + $extra;
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
	abstract protected function subject_class(): string;

	/**
	 * Provides platform-/runtime-specific context fields.
	 *
	 * Example: ['system' => 'core']
	 *
	 * @since 0.1.0
	 *
	 * @return array<string, mixed> The platform-specific context entries.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
	 */
	abstract protected function platform_context(): array;
}
