<?php

declare(strict_types=1);

namespace Fundrik\Core\Application\Campaigns;

use Fundrik\Core\Application\Campaigns\Interfaces\CampaignRepositoryInterface;
use Fundrik\Core\Domain\Campaigns\Campaign;
use Fundrik\Core\Domain\Campaigns\CampaignFactory;
use Fundrik\Core\Domain\EntityId;

/**
 * Service for coordinating access to campaign data and behavior.
 *
 * @since 1.0.0
 */
final readonly class CampaignService {

	/**
	 * CampaignService constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param CampaignFactory             $factory Factory to create Campaign objects from DTOs.
	 * @param CampaignRepositoryInterface $repository Repository to retrieve campaign DTOs from the database.
	 */
	public function __construct(
		private CampaignFactory $factory,
		private CampaignRepositoryInterface $repository
	) {}

	/**
	 * Get a campaign by its ID.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId $id The campaign ID.
	 *
	 * @return Campaign|null The campaign if found, or null if not found.
	 */
	public function get_campaign_by_id( EntityId $id ): ?Campaign {

		$campaign_dto = $this->repository->get_by_id( $id );

		return $campaign_dto ? $this->factory->create( $campaign_dto ) : null;
	}

	/**
	 * Get all campaigns.
	 *
	 * @since 1.0.0
	 *
	 * @return Campaign[] An array of campaigns.
	 */
	public function get_all_campaigns(): array {

		$dto_list = $this->repository->get_all();

		return array_map(
			fn( CampaignDto $dto ): Campaign => $this->factory->create( $dto ),
			$dto_list
		);
	}

	/**
	 * Save a campaign (create or update).
	 *
	 * @param CampaignDto $dto The campaign DTO to save.
	 */
	public function save_campaign( CampaignDto $dto ): bool {

		$campaign = $this->factory->create( $dto );

		return $this->repository->exists( $campaign )
			? $this->repository->update( $campaign )
			: $this->repository->insert( $campaign );
	}

	/**
	 * Delete a campaign by its ID.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId $id The ID of the campaign to delete.
	 *
	 * @return bool True if the campaign was successfully deleted, false otherwise.
	 */
	public function delete_campaign( EntityId $id ): bool {

		return $this->repository->delete( $id );
	}
}
