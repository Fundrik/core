<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application\Loggers;

use Fundrik\Core\Components\Campaigns\Application\Exceptions\CampaignAssemblerExceptionInterface;
use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositoryExceptionInterface;
use Fundrik\Core\Components\Campaigns\Application\Services\CampaignQueryService;

/**
 * Logs application-level operations of the CampaignQueryService.
 *
 * @since 0.1.0
 */
final readonly class CampaignQueryServiceLogger extends AbstractCampaignServiceLogger {

	private const OPERATION_FIND_BY_ID = 'find_campaign_by_id';
	private const OPERATION_FIND_ALL = 'find_all_campaigns';

	/**
	 * Logs the start of a find-by-ID operation (debug).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID being searched.
	 *
	 * @codeCoverageIgnore
	 */
	public function log_find_by_id_started( int|string $id ): void {

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
	public function log_find_by_id_failed_repository( int|string $id, CampaignRepositoryExceptionInterface $e ): void {

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
	public function log_find_by_id_not_found( int|string $id ): void {

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

	/**
	 * Logs assembler failure during a find-by-ID operation (error).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID being processed.
	 * @param CampaignAssemblerExceptionInterface $e The assembler exception that occurred.
	 */
	public function log_find_by_id_failed_assembler( int|string $id, CampaignAssemblerExceptionInterface $e ): void {

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

	/**
	 * Logs successful completion of a find-by-ID operation (debug).
	 *
	 * @since 0.1.0
	 *
	 * @param int|string $id The campaign ID found.
	 *
	 * @codeCoverageIgnore
	 */
	public function log_find_by_id_succeeded( int|string $id ): void {

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
	public function log_find_all_started(): void {

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
	public function log_find_all_failed_repository( CampaignRepositoryExceptionInterface $e ): void {

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
	public function log_find_all_failed_assembler( CampaignAssemblerExceptionInterface $e ): void {

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
	public function log_find_all_succeeded( int $count ): void {

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
	 * Returns the class name of the subject being logged.
	 *
	 * @since 0.1.0
	 *
	 * @return string The fully qualified class name of the subject service to attribute the log entries to.
	 *
	 * @phpstan-return class-string
	 */
	protected function subject_class(): string {

		return CampaignQueryService::class;
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
