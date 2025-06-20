<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain\Campaigns;

use Fundrik\Core\Application\Campaigns\CampaignDto;
use Fundrik\Core\Domain\EntityId;

/**
 * Responsible for creating Campaign instances.
 *
 * It validates input data and ensures that the Campaign is correctly initialized.
 *
 * @since 1.0.0
 */
final readonly class CampaignFactory {

	/**
	 * Create a Campaign instance from a CampaignDto.
	 *
	 * @since 1.0.0
	 *
	 * @param CampaignDto $dto The data transfer object containing campaign data.
	 *
	 * @return Campaign A new Campaign instance constructed from the DTO.
	 *
	 * @throws InvalidEntityIdException       If the ID is invalid.
	 * @throws InvalidCampaignTitleException  If the title is invalid.
	 * @throws InvalidCampaignTargetException If the target data is inconsistent.
	 */
	public function create( CampaignDto $dto ): Campaign {

		$id     = EntityId::create( $dto->id );
		$title  = CampaignTitle::create( $dto->title );
		$target = CampaignTarget::create( $dto->has_target, $dto->target_amount );

		$campaign = new Campaign(
			id: $id,
			title: $title,
			is_enabled: $dto->is_enabled,
			is_open: $dto->is_open,
			target: $target,
		);

		return $campaign;
	}
}
