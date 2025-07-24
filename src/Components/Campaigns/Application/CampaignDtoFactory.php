<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application;

use Fundrik\Core\Components\Campaigns\Application\Exceptions\CampaignDtoFactoryException;
use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Support\ArrayExtractor;
use Fundrik\Core\Support\Exceptions\ArrayExtractionException;

/**
 * Creates CampaignDto objects from arrays or domain entities.
 *
 * @since 1.0.0
 */
final readonly class CampaignDtoFactory {

	/**
	 * Creates a CampaignDto from a raw associative array.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, scalar> $data The input data array.
	 *
	 * @phpstan-param array{
	 *   id: int|string,
	 *   title: string,
	 *   is_active: bool,
	 *   is_open: bool,
	 *   has_target: bool,
	 *   target_amount: int
	 * } $data
	 *
	 * Data keys:
	 *  - id The campaign ID
	 *  - title The campaign title
	 *  - is_active Whether the campaign is active
	 *  - is_open Whether the campaign is open for donations
	 *  - has_target Whether the campaign has a target
	 *  - target_amount The campaign target amount.
	 *
	 * @return CampaignDto The DTO constructed from array values.
	 */
	public function from_array( array $data ): CampaignDto {

		try {
			return new CampaignDto(
				id: ArrayExtractor::extract_id_required( $data, 'id' ),
				title: ArrayExtractor::extract_string_required( $data, 'title' ),
				is_active: ArrayExtractor::extract_bool_required( $data, 'is_active' ),
				is_open: ArrayExtractor::extract_bool_required( $data, 'is_open' ),
				has_target: ArrayExtractor::extract_bool_required( $data, 'has_target' ),
				target_amount: ArrayExtractor::extract_int_required( $data, 'target_amount' ),
			);
		} catch ( ArrayExtractionException $e ) {
			throw new CampaignDtoFactoryException(
				'Failed to create CampaignDto from array: ' . $e->getMessage(),
				previous: $e,
			);
		}
	}

	/**
	 * Creates a CampaignDto from a domain Campaign entity.
	 *
	 * @since 1.0.0
	 *
	 * @param Campaign $campaign The source campaign entity.
	 *
	 * @return CampaignDto The DTO representation of the campaign.
	 */
	public function from_campaign( Campaign $campaign ): CampaignDto {

		return new CampaignDto(
			id: $campaign->get_id(),
			title: $campaign->get_title(),
			is_active: $campaign->is_active(),
			is_open: $campaign->is_open(),
			has_target: $campaign->has_target(),
			target_amount: $campaign->get_target_amount(),
		);
	}
}
