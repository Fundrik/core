<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain\Campaigns;

use Fundrik\Core\Application\Campaigns\CampaignDto;
use Fundrik\Core\Domain\EntityId;

/**
 * Domain factory responsible for creating Campaign entities from DTOs.
 *
 * Performs validation and ensures domain invariants.
 *
 * @since 1.0.0
 */
final readonly class CampaignFactory {

	/**
	 * Creates a Campaign domain entity from a DTO.
	 *
	 * @since 1.0.0
	 *
	 * @param CampaignDto $dto Campaign DTO.
	 *
	 * @return Campaign New Campaign entity.
	 *
	 * @throws InvalidEntityIdException       If the ID is invalid.
	 * @throws InvalidCampaignTitleException  If the title is invalid.
	 * @throws InvalidCampaignTargetException If the target data is invalid.
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
