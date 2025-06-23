<?php

declare(strict_types=1);

namespace Fundrik\Core\Application\Campaigns;

use Fundrik\Core\Domain\Campaigns\Campaign;
use Fundrik\Core\Support\TypeCaster;

/**
 * Factory for creating CampaignDto objects from arrays or domain entities.
 *
 * Resides in Application layer bridging between raw data and domain models.
 * Assumes input data is already validated or trusted.
 *
 * @since 1.0.0
 */
final readonly class CampaignDtoFactory {

	/**
	 * Creates a CampaignDto from a raw associative array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, int|string|bool> $data Raw campaign data with keys:
	 *      - id (int|string) Campaign ID
	 *      - title (string) Campaign title
	 *      - is_enabled (bool) Whether campaign is enabled (visible/active)
	 *      - is_open (bool) Whether campaign is open
	 *      - has_target (bool) Whether campaign has a fundraising target
	 *      - target_amount (int) The fundraising target amount.
	 *
	 * @return CampaignDto The constructed DTO.
	 */
	public function from_array( array $data ): CampaignDto {

		return new CampaignDto(
			id: TypeCaster::to_id( $data['id'] ),
			title: TypeCaster::to_string( $data['title'] ),
			is_enabled: TypeCaster::to_bool( $data['is_enabled'] ),
			is_open: TypeCaster::to_bool( $data['is_open'] ),
			has_target: TypeCaster::to_bool( $data['has_target'] ),
			target_amount: TypeCaster::to_int( $data['target_amount'] ),
		);
	}

	/**
	 * Creates a CampaignDto from a Campaign domain entity.
	 *
	 * @since 1.0.0
	 *
	 * @param Campaign $campaign The domain Campaign instance.
	 *
	 * @return CampaignDto The corresponding DTO.
	 */
	public function from_campaign( Campaign $campaign ): CampaignDto {

		return new CampaignDto(
			id: $campaign->get_id(),
			title: $campaign->get_title(),
			is_enabled: $campaign->is_enabled(),
			is_open: $campaign->is_open(),
			has_target: $campaign->has_target(),
			target_amount: $campaign->get_target_amount(),
		);
	}
}
