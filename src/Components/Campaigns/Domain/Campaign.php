<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Domain;

use Fundrik\Core\Components\Shared\Domain\EntityId;

/**
 * Represents a fundraising campaign.
 *
 * @since 1.0.0
 */
final readonly class Campaign {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId $id The campaign ID.
	 * @param CampaignTitle $title The campaign title.
	 * @param bool $is_active Whether the campaign is active.
	 * @param bool $is_open Whether the campaign is open for donations.
	 * @param CampaignTarget $target The campaign target.
	 */
	public function __construct(
		private EntityId $id,
		private CampaignTitle $title,
		private bool $is_active,
		private bool $is_open,
		private CampaignTarget $target,
	) {}

	/**
	 * Returns the campaign ID.
	 *
	 * @since 1.0.0
	 *
	 * @return int|string The campaign ID (positive integer or UUID).
	 */
	public function get_id(): int|string {

		return $this->id->value;
	}

	/**
	 * Returns the campaign title.
	 *
	 * @since 1.0.0
	 *
	 * @return string The campaign title string.
	 */
	public function get_title(): string {

		return $this->title->value;
	}

	/**
	 * Returns whether the campaign is active.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the campaign is active.
	 */
	public function is_active(): bool {

		return $this->is_active;
	}

	/**
	 * Returns whether the campaign is open for donations.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the campaign is currently open.
	 */
	public function is_open(): bool {

		return $this->is_open;
	}

	/**
	 * Returns whether the campaign has a target.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the campaign has a target.
	 */
	public function has_target(): bool {

		return $this->target->is_enabled;
	}

	/**
	 * Returns the campaign target amount.
	 *
	 * Returns zero if targeting is disabled.
	 *
	 * @since 1.0.0
	 *
	 * @return int The target amount in minor units.
	 */
	public function get_target_amount(): int {

		return $this->target->amount;
	}
}
