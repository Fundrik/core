<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain\Campaigns;

use Fundrik\Core\Domain\Campaigns\Exceptions\InvalidCampaignTargetException;

/**
 * Value Object representing campaign fundraising target.
 *
 * Enforces the following invariants:
 * - If targeting is enabled (`$is_enabled === true`), the target amount must be a positive integer.
 * - If targeting is disabled (`$is_enabled === false`), the target amount must be exactly zero.
 *
 * This ensures internal consistency between the intent to fundraise and the specified target amount.
 *
 * @since 1.0.0
 */
final readonly class CampaignTarget {

	/**
	 * Private constructor, use factory method.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $is_enabled Whether targeting is enabled.
	 * @param int $amount Target amount.
	 */
	private function __construct(
		public bool $is_enabled,
		public int $amount,
	) {}

	/**
	 * Factory method to create a CampaignTarget.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $is_enabled Whether target is enabled.
	 * @param int $amount Target amount.
	 *
	 * @return self New CampaignTarget instance.
	 */
	public static function create( bool $is_enabled, int $amount ): self {

		if ( $is_enabled && $amount <= 0 ) {
			throw new InvalidCampaignTargetException(
				"Target amount must be positive when targeting is enabled, given {$amount}",
			);
		}

		if ( ! $is_enabled && $amount !== 0 ) {
			throw new InvalidCampaignTargetException(
				"Target amount should be zero when targeting is disabled, given {$amount}",
			);
		}

		return new self( $is_enabled, $amount );
	}
}
