<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application;

use Fundrik\Core\Components\Campaigns\Application\Exceptions\CampaignAssemblerException;
use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTarget;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTitle;
use Fundrik\Core\Components\Campaigns\Domain\Exceptions\InvalidCampaignTargetException;
use Fundrik\Core\Components\Campaigns\Domain\Exceptions\InvalidCampaignTitleException;
use Fundrik\Core\Components\Shared\Domain\EntityId;
use Fundrik\Core\Components\Shared\Domain\Exceptions\InvalidEntityIdException;

/**
 * Assembles Campaign domain entities from DTO.
 *
 * @since 0.1.0
 */
final readonly class CampaignAssembler {

	/**
	 * Creates a Campaign entity from a DTO.
	 *
	 * @since 0.1.0
	 *
	 * @param CampaignDto $dto The DTO representing campaign data.
	 *
	 * @return Campaign The domain entity constructed from the DTO.
	 *
	 * @throws CampaignAssemblerException When the DTO contains invalid data.
	 */
	public function from_dto( CampaignDto $dto ): Campaign {

		try {

			$id = EntityId::create( $dto->id );
			$title = CampaignTitle::create( $dto->title );
			$target = CampaignTarget::create( $dto->has_target, $dto->target_amount );

			return new Campaign(
				id: $id,
				title: $title,
				is_active: $dto->is_active,
				is_open: $dto->is_open,
				target: $target,
			);
		} catch ( InvalidEntityIdException | InvalidCampaignTitleException | InvalidCampaignTargetException $e ) {

			throw new CampaignAssemblerException(
				sprintf( 'Cannot assemble Campaign from DTO: %s', $e->getMessage() ),
				previous: $e,
			);
		}
	}
}
