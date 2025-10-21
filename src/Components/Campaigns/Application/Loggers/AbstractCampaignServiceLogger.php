<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application\Loggers;

use Psr\Log\LoggerInterface;

/**
 * Provides structured, platform-agnostic logging for CampaignService operations.
 *
 * @since 0.1.0
 */
abstract readonly class AbstractCampaignServiceLogger {

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
