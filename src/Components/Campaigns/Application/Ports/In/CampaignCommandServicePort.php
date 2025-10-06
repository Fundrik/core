<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application\Ports\In;

// phpcs:ignore SlevomatCodingStandard.Namespaces.UnusedUses.UnusedUse
use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositoryExceptionInterface;
use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTarget;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTitle;
use Fundrik\Core\Components\Shared\Domain\EntityId;

/**
 * Defines the inbound port interface for application-level operations for managing campaigns.
 *
 * @since 0.1.0
 */
interface CampaignCommandServicePort {

	/**
	 * Creates a new campaign.
	 *
	 * @since 0.1.0
	 *
	 * @param Campaign $campaign The campaign to create.
	 *
	 * @throws CampaignRepositoryExceptionInterface When the repository insert fails.
	 */
	public function create_campaign( Campaign $campaign ): void;

	/**
	 * Creates a new campaign without a predefined ID.
	 *
	 * This method should be used when the underlying persistence mechanism
	 * assigns the ID automatically (for example, auto-increment column).
	 *
	 * @since 0.1.0
	 *
	 * @param CampaignTitle $title The validated campaign title.
	 * @param bool $is_active Whether the campaign is active.
	 * @param bool $is_open Whether the campaign is open for donations.
	 * @param CampaignTarget $target The validated campaign target.
	 *
	 * @return Campaign The created campaign with its assigned ID.
	 *
	 * @throws CampaignRepositoryExceptionInterface When the repository insert fails.
	 */
	public function create_campaign_without_id(
		CampaignTitle $title,
		bool $is_active,
		bool $is_open,
		CampaignTarget $target,
	): Campaign;

	/**
	 * Updates an existing campaign.
	 *
	 * @since 0.1.0
	 *
	 * @param Campaign $campaign The campaign to update.
	 *
	 * @throws CampaignRepositoryExceptionInterface When the repository update fails.
	 */
	public function update_campaign( Campaign $campaign ): void;

	/**
	 * Saves the given campaign.
	 *
	 * Creates a new campaign or updates an existing one.
	 *
	 * @since 0.1.0
	 *
	 * @param Campaign $campaign The campaign to save.
	 *
	 * @throws CampaignRepositoryExceptionInterface When the repository save fails.
	 */
	public function save_campaign( Campaign $campaign ): void;

	/**
	 * Deletes a campaign by its ID.
	 *
	 * @since 0.1.0
	 *
	 * @param EntityId $id The ID of the campaign to delete.
	 *
	 * @throws CampaignRepositoryExceptionInterface When the repository delete fails.
	 */
	public function delete_campaign( EntityId $id ): void;
}
