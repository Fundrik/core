<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application;

use Fundrik\Core\Components\Campaigns\Application\Ports\In\CampaignServicePortInterface;
use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositoryPortInterface;
use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Components\Shared\Domain\EntityId;

/**
 * Provides application-level operations for managing campaigns.
 *
 * @since 1.0.0
 */
final readonly class CampaignService implements CampaignServicePortInterface {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param CampaignAssembler $assembler Converts between DTOs and domain entities.
	 * @param CampaignRepositoryPortInterface $repository Provides access to campaign storage.
	 */
	public function __construct(
		private CampaignAssembler $assembler,
		private CampaignRepositoryPortInterface $repository,
	) {}

	/**
	 * Finds a campaign by its ID.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId $id The ID of the campaign to retrieve.
	 *
	 * @return Campaign|null The campaign if found, otherwise null.
	 */
	public function find_campaign_by_id( EntityId $id ): ?Campaign {

		$campaign_dto = $this->repository->get_by_id( $id );

		return $campaign_dto !== null ? $this->assembler->from_dto( $campaign_dto ) : null;
	}

	/**
	 * Retrieves all campaigns.
	 *
	 * @since 1.0.0
	 *
	 * @return array<Campaign> All available campaign entities.
	 */
	public function find_all_campaigns(): array {

		$dto_list = $this->repository->get_all();

		return array_map(
			fn ( CampaignDto $dto ): Campaign => $this->assembler->from_dto( $dto ),
			$dto_list,
		);
	}

	/**
	 * Saves the given campaign.
	 *
	 * Creates a new campaign or updates an existing one, based on its ID.
	 *
	 * @since 1.0.0
	 *
	 * @param CampaignDto $dto The campaign to save.
	 *
	 * @return bool True if the operation succeeded.
	 */
	public function save_campaign( CampaignDto $dto ): bool {

		$campaign = $this->assembler->from_dto( $dto );

		return $this->repository->exists( $campaign )
			? $this->repository->update( $campaign )
			: $this->repository->insert( $campaign );
	}

	/**
	 * Deletes a campaign by its ID.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId $id The ID of the campaign to delete.
	 *
	 * @return bool True if the campaign was successfully deleted.
	 */
	public function delete_campaign( EntityId $id ): bool {

		return $this->repository->delete( $id );
	}
}
