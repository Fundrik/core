<?php

declare(strict_types=1);

namespace Fundrik\Core\Application\Campaigns\Interfaces;

use Fundrik\Core\Application\Campaigns\CampaignDto;
use Fundrik\Core\Domain\Campaigns\Campaign;
use Fundrik\Core\Domain\EntityId;

/**
 * Interface for the Campaign repository.
 *
 * @since 1.0.0
 */
interface CampaignRepositoryInterface {

	/**
	 * Get a campaign by its ID.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId $id The campaign ID.
	 *
	 * @return CampaignDto|null The campaign DTO if found, or null if not found.
	 */
	public function get_by_id( EntityId $id ): ?CampaignDto;

	/**
	 * Get all campaigns.
	 *
	 * @since 1.0.0
	 *
	 * @return CampaignDto[] An array of campaign DTOs.
	 */
	public function get_all(): array;

	/**
	 * Check if a campaign exists.
	 *
	 * @since 1.0.0
	 *
	 * @param Campaign $campaign The campaign entity to check.
	 *
	 * @return bool True if the campaign exists, false otherwise.
	 */
	public function exists( Campaign $campaign ): bool;

	/**
	 * Insert a new campaign.
	 *
	 * @since 1.0.0
	 *
	 * @param Campaign $campaign The campaign entity to insert.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function insert( Campaign $campaign ): bool;

	/**
	 * Update an existing campaign.
	 *
	 * @since 1.0.0
	 *
	 * @param Campaign $campaign The campaign entity to update.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function update( Campaign $campaign ): bool;

	/**
	 * Delete a campaign by its ID.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId $id The ID of the campaign to delete.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function delete( EntityId $id ): bool;
}
