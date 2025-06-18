<?php

declare(strict_types=1);

namespace Fundrik\Core\Application\Campaigns;

/**
 * Represents a raw snapshot of campaign data.
 *
 * This DTO is typically created from database records
 * and used to pass data into factory.
 *
 * @since 1.0.0
 *
 * @codeCoverageIgnore
 */
final readonly class CampaignDto {

	/**
	 * CampaignDto constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $id               The campaign ID (integer or UUID).
	 * @param string     $title            The title of the campaign.
	 * @param bool       $is_enabled       Whether the campaign is currently enabled (visible and accessible).
	 * @param bool       $is_open          Whether the campaign is currently open.
	 * @param bool       $has_target       Whether the campaign has a target goal.
	 * @param int        $target_amount    The target amount (if any) for the campaign.
	 */
	public function __construct(
		public int|string $id,
		public string $title,
		public bool $is_enabled,
		public bool $is_open,
		public bool $has_target,
		public int $target_amount,
	) {
	}
}
