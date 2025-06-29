<?php

declare(strict_types=1);

namespace Fundrik\Core\Application\Campaigns;

/**
 * Data Transfer Object (DTO) representing campaign data.
 *
 * Used in the Application layer for data exchange between infrastructure and domain layers.
 * Carries raw or normalized data, typically retrieved from storage or external sources.
 *
 * @since 1.0.0
 */
final readonly class CampaignDto {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $id Campaign ID (int or UUID string).
	 * @param string $title Campaign title.
	 * @param bool $is_enabled Whether campaign is enabled (visible/active).
	 * @param bool $is_open Whether campaign is open.
	 * @param bool $has_target Whether campaign has a fundraising target.
	 * @param int $target_amount The fundraising target amount.
	 */
	public function __construct(
		public int|string $id,
		public string $title,
		public bool $is_enabled,
		public bool $is_open,
		public bool $has_target,
		public int $target_amount,
	) {}
}
