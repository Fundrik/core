<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application;

/**
 * Carries campaign data between infrastructure and domain layers.
 *
 * @since 1.0.0
 */
final readonly class CampaignDto {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $id The campaign ID (positive integer or UUID).
	 * @param string $title The campaign title.
	 * @param bool $is_active Whether the campaign is active.
	 * @param bool $is_open Whether the campaign is open for donations.
	 * @param bool $has_target Whether the campaign has a target.
	 * @param int $target_amount The campaign target amount.
	 */
	public function __construct(
		public int|string $id,
		public string $title,
		public bool $is_active,
		public bool $is_open,
		public bool $has_target,
		public int $target_amount,
	) {}
}
