<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Domain;

use Fundrik\Core\Components\Campaigns\Domain\Exceptions\InvalidCampaignTargetException;

/**
 * Represents the fundraising target of a campaign.
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
	 * @param int $amount The fundraising target amount.
	 */
	private function __construct(
		public bool $is_enabled,
		public int $amount,
	) {}

	/**
	 * Creates a campaign target value object.
	 *
	 * Validates consistency between enablement flag and amount:
	 * - If enabled, amount must be positive.
	 * - If disabled, amount must be zero.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $is_enabled Whether targeting is enabled.
	 * @param int $amount The fundraising target amount.
	 *
	 * @return self The campaign target value object.
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
