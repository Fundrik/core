<?php

declare(strict_types=1);

namespace Fundrik\Core\Application\Campaigns\Interfaces;

use Fundrik\Core\Application\Campaigns\CampaignDto;
use Fundrik\Core\Domain\Campaigns\Campaign;
use Fundrik\Core\Domain\EntityId;

/**
 * Application layer repository interface for Campaign persistence.
 *
 * Abstracts data storage and retrieval for Campaigns using DTOs.
 *
 * @since 1.0.0
 */
interface CampaignRepositoryInterface {

	/**
	 * Retrieves a campaign DTO by ID.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId $id Campaign identifier.
	 *
	 * @return CampaignDto|null Campaign DTO if found, null otherwise.
	 */
	public function get_by_id( EntityId $id ): ?CampaignDto;

	/**
	 * Retrieves all campaign DTOs.
	 *
	 * @since 1.0.0
	 *
	 * @return array<CampaignDto> List of all campaign DTOs.
	 */
	public function get_all(): array;

	/**
	 * Checks if a campaign exists in storage.
	 *
	 * @since 1.0.0
	 *
	 * @param Campaign $campaign The domain campaign entity.
	 *
	 * @return bool True if exists, false otherwise.
	 */
	public function exists( Campaign $campaign ): bool;

	/**
	 * Inserts a new campaign into storage.
	 *
	 * @since 1.0.0
	 *
	 * @param Campaign $campaign Domain campaign entity to insert.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function insert( Campaign $campaign ): bool;

	/**
	 * Updates an existing campaign in storage.
	 *
	 * @since 1.0.0
	 *
	 * @param Campaign $campaign Domain campaign entity to update.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function update( Campaign $campaign ): bool;

	/**
	 * Deletes a campaign by ID from storage.
	 *
	 * @since 1.0.0
	 *
	 * @param EntityId $id Campaign identifier.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function delete( EntityId $id ): bool;
}
