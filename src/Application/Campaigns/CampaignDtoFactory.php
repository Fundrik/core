<?php

declare(strict_types=1);

namespace Fundrik\Core\Application\Campaigns;

use Fundrik\Core\Application\Campaigns\Exceptions\InvalidCampaignDtoInputException;
use Fundrik\Core\Domain\Campaigns\Campaign;
use Fundrik\Core\Support\ArrayExtractor;
use Fundrik\Core\Support\Exceptions\ArrayExtractionException;

/**
 * Factory for creating CampaignDto objects from arrays or domain entities.
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

		try {
			return new CampaignDto(
				id: ArrayExtractor::extract_id_required( $data, 'id' ),
				title: ArrayExtractor::extract_string_required( $data, 'title' ),
				is_enabled: ArrayExtractor::extract_bool_required( $data, 'is_enabled' ),
				is_open: ArrayExtractor::extract_bool_required( $data, 'is_open' ),
				has_target: ArrayExtractor::extract_bool_required( $data, 'has_target' ),
				target_amount: ArrayExtractor::extract_int_required( $data, 'target_amount' ),
			);
		} catch ( ArrayExtractionException $e ) {
			throw new InvalidCampaignDtoInputException(
				'Failed to create CampaignDto from array: ' . $e->getMessage(),
				previous: $e,
			);
		}
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
