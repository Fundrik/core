<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain\Campaigns;

use Fundrik\Core\Domain\EntityId;

/**
 * Represents a campaign.
 *
 * @since 1.0.0
 */
final readonly class Campaign {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId       $id Campaign ID (can be either int or UUID).
	 * @param CampaignTitle  $title Campaign title.
	 * @param bool           $is_enabled Flag indicating if the campaign is enabled (visible and accessible).
	 * @param bool           $is_open Flag indicating if the campaign is open.
	 * @param CampaignTarget $target Campaign target (enabled status and amount).
	 */
	public function __construct(
		private EntityId $id,
		private CampaignTitle $title,
		private bool $is_enabled,
		private bool $is_open,
		private CampaignTarget $target,
	) {}

	/**
	 * Get campaign id.
	 *
	 * @since 1.0.0
	 *
	 * @return int|string Campaign id.
	 */
	public function get_id(): int|string {

		return $this->id->value;
	}

	/**
	 * Get campaign title.
	 *
	 * @since 1.0.0
	 *
	 * @return int|string Campaign title.
	 */
	public function get_title(): string {

		return $this->title->value;
	}

	/**
	 * Check if campaign is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if enabled, false otherwise.
	 */
	public function is_enabled(): bool {

		return $this->is_enabled;
	}

	/**
	 * Check if campaign is open.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if open, false otherwise.
	 */
	public function is_open(): bool {

		return $this->is_open;
	}

	/**
	 * Check if target is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if target is enabled.
	 */
	public function has_target(): bool {

		return $this->target->is_enabled;
	}

	/**
	 * Get target amount for campaign.
	 *
	 * @since 1.0.0
	 *
	 * @return int Target amount.
	 */
	public function get_target_amount(): int {

		return $this->target->amount;
	}
}
