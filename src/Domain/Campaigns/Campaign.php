<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain\Campaigns;

use Fundrik\Core\Domain\EntityId;

/**
 * Domain entity representing a fundraising campaign.
 *
 * Encapsulates campaign state and business invariants.
 *
 * @since 1.0.0
 */
final readonly class Campaign {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId       $id Campaign identifier.
	 * @param CampaignTitle  $title Campaign title value object.
	 * @param bool           $is_enabled Whether campaign is enabled.
	 * @param bool           $is_open Whether campaign is open.
	 * @param CampaignTarget $target Campaign target value object.
	 */
	public function __construct(
		private EntityId $id,
		private CampaignTitle $title,
		private bool $is_enabled,
		private bool $is_open,
		private CampaignTarget $target,
	) {}

	/**
	 * Returns the campaign identifier as int or UUID string.
	 *
	 * @since 1.0.0
	 *
	 * @return int|string Unique identifier for the campaign.
	 */
	public function get_id(): int|string {

		return $this->id->value;
	}

	/**
	 * Returns the campaign title string.
	 *
	 * @since 1.0.0
	 *
	 * @return string Title of the campaign.
	 */
	public function get_title(): string {

		return $this->title->value;
	}

	/**
	 * Determines whether the campaign is enabled.
	 *
	 * Enabled campaigns are visible and accessible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if campaign is enabled.
	 */
	public function is_enabled(): bool {

		return $this->is_enabled;
	}

	/**
	 * Determines whether the campaign is open for donations.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if campaign is currently open.
	 */
	public function is_open(): bool {

		return $this->is_open;
	}

	/**
	 * Determines whether the campaign has a target goal.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if target is defined.
	 */
	public function has_target(): bool {

		return $this->target->is_enabled;
	}

	/**
	 * Returns the target amount for the campaign.
	 *
	 * Returns zero if targeting is disabled.
	 *
	 * @since 1.0.0
	 *
	 * @return int Campaign target amount in minor units (e.g., cents).
	 */
	public function get_target_amount(): int {

		return $this->target->amount;
	}
}
