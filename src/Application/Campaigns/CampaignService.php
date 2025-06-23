<?php

declare(strict_types=1);

namespace Fundrik\Core\Application\Campaigns;

use Fundrik\Core\Application\Campaigns\Interfaces\CampaignRepositoryInterface;
use Fundrik\Core\Domain\Campaigns\Campaign;
use Fundrik\Core\Domain\Campaigns\CampaignFactory;
use Fundrik\Core\Domain\EntityId;

/**
 * Application service for use cases involving Campaign domain entities.
 *
 * Coordinates domain logic, DTOs, and persistence.
 * Handles orchestration of campaign-related operations.
 *
 * @since 1.0.0
 */
final readonly class CampaignService {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param CampaignFactory $factory Factory for domain Campaign creation.
	 * @param CampaignRepositoryInterface $repository Repository to access campaign data.
	 */
	public function __construct(
		private CampaignFactory $factory,
		private CampaignRepositoryInterface $repository,
	) {}

	/**
	 * Retrieves a Campaign entity by its ID.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId $id Campaign identifier.
	 *
	 * @return Campaign|null Returns Campaign if found, otherwise null.
	 *
	 * @note Exceptions from the repository are propagated up and must be handled by caller.
	 */
	public function get_campaign_by_id( EntityId $id ): ?Campaign {

		$campaign_dto = $this->repository->get_by_id( $id );

		return $campaign_dto
			? $this->factory->create( $campaign_dto )
			: null;
	}

	/**
	 * Retrieves all Campaign entities.
	 *
	 * @since 1.0.0
	 *
	 * @return array<Campaign> Array of Campaign objects.
	 *
	 * @note Exceptions from the repository are propagated and must be handled by caller.
	 */
	public function get_all_campaigns(): array {

		$dto_list = $this->repository->get_all();

		return array_map(
			fn ( CampaignDto $dto ): Campaign => $this->factory->create( $dto ),
			$dto_list,
		);
	}

	/**
	 * Saves (creates or updates) a campaign.
	 *
	 * @since 1.0.0
	 *
	 * @param CampaignDto $dto Campaign data transfer object.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function save_campaign( CampaignDto $dto ): bool {

		$campaign = $this->factory->create( $dto );

		return $this->repository->exists( $campaign )
			? $this->repository->update( $campaign )
			: $this->repository->insert( $campaign );
	}

	/**
	 * Deletes a campaign by ID.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId $id Campaign identifier.
	 *
	 * @return bool True if deletion succeeded, false otherwise.
	 */
	public function delete_campaign( EntityId $id ): bool {

		return $this->repository->delete( $id );
	}
}
