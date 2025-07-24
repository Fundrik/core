<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application\Ports\Out;

use Fundrik\Core\Components\Campaigns\Application\CampaignDto;
use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Components\Shared\Domain\EntityId;

/**
 * Defines the outbound port for accessing campaign persistence.
 *
 * This interface represents the storage contract required by the application layer.
 * It allows the service layer to remain decoupled from specific infrastructure details.
 *
 * @since 1.0.0
 */
interface CampaignRepositoryPortInterface {

	/**
	 * Fetches the DTO of a campaign by its ID.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId $id The ID of the campaign to retrieve.
	 *
	 * @return CampaignDto|null The campaign data if found, null otherwise.
	 */
	public function get_by_id( EntityId $id ): ?CampaignDto;

	/**
	 * Fetches all available campaign DTOs.
	 *
	 * @since 1.0.0
	 *
	 * @return array<CampaignDto> The list of campaign data objects.
	 */
	public function get_all(): array;

	/**
	 * Returns whether the campaign exists in storage.
	 *
	 * @since 1.0.0
	 *
	 * @param Campaign $campaign The campaign entity to check.
	 *
	 * @return bool True if the campaign exists, false otherwise.
	 */
	public function exists( Campaign $campaign ): bool;

	/**
	 * Inserts a new campaign into storage.
	 *
	 * @since 1.0.0
	 *
	 * @param Campaign $campaign The campaign to insert.
	 *
	 * @return bool True on success, false otherwise.
	 */
	public function insert( Campaign $campaign ): bool;

	/**
	 * Updates an existing campaign in storage.
	 *
	 * @since 1.0.0
	 *
	 * @param Campaign $campaign The campaign to update.
	 *
	 * @return bool True on success, false otherwise.
	 */
	public function update( Campaign $campaign ): bool;

	/**
	 * Removes a campaign from storage by its ID.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId $id The ID of the campaign to delete.
	 *
	 * @return bool True on successful deletion, false otherwise.
	 */
	public function delete( EntityId $id ): bool;
}
