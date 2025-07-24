<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application\Ports\In;

use Fundrik\Core\Components\Campaigns\Application\CampaignDto;
use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Components\Shared\Domain\EntityId;

/**
 * Defines the inbound port interface for application-level operations for managing campaigns.
 *
 * @since 1.0.0
 */
interface CampaignServicePortInterface {

	/**
	 * Retrieves a campaign by its ID.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId $id The ID of the campaign to retrieve.
	 *
	 * @return Campaign|null The campaign if found, otherwise null.
	 */
	public function find_campaign_by_id( EntityId $id ): ?Campaign;

	/**
	 * Retrieves all campaigns.
	 *
	 * @since 1.0.0
	 *
	 * @return array<Campaign> All available campaign entities.
	 */
	public function find_all_campaigns(): array;

	/**
	 * Saves the given campaign.
	 *
	 * Creates a new campaign or updates an existing one.
	 *
	 * @since 1.0.0
	 *
	 * @param CampaignDto $dto The campaign to save.
	 *
	 * @return bool True if the operation succeeded.
	 */
	public function save_campaign( CampaignDto $dto ): bool;

	/**
	 * Deletes a campaign by its ID.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId $id The ID of the campaign to delete.
	 *
	 * @return bool True if the campaign was successfully deleted.
	 */
	public function delete_campaign( EntityId $id ): bool;
}
