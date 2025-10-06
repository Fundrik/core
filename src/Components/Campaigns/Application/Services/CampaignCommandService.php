<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application\Services;

use Fundrik\Core\Components\Campaigns\Application\Events\CampaignCreatedEvent;
use Fundrik\Core\Components\Campaigns\Application\Events\CampaignDeletedEvent;
use Fundrik\Core\Components\Campaigns\Application\Events\CampaignUpdatedEvent;
use Fundrik\Core\Components\Campaigns\Application\Loggers\CampaignCommandServiceLogger;
use Fundrik\Core\Components\Campaigns\Application\Loggers\CampaignSaveLogAction;
use Fundrik\Core\Components\Campaigns\Application\Ports\In\CampaignCommandServicePort;
use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositoryExceptionInterface;
use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositoryPort;
use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositorySaveResult;
use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTarget;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTitle;
use Fundrik\Core\Components\Shared\Application\Ports\Out\EventBusPort;
use Fundrik\Core\Components\Shared\Domain\EntityId;
use LogicException;
use Throwable;

/**
 * Provides application-level commands for managing campaigns.
 *
 * @since 0.1.0
 */
final readonly class CampaignCommandService implements CampaignCommandServicePort {

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param CampaignRepositoryPort $repository Provides access to campaign storage.
	 * @param CampaignCommandServiceLogger $logger Logs application-level operations and outcomes.
	 * @param EventBusPort $event_bus Publishes application events to subscribed listeners.
	 */
	public function __construct(
		private CampaignRepositoryPort $repository,
		private CampaignCommandServiceLogger $logger,
		private EventBusPort $event_bus,
	) {}

	/**
	 * Creates a new campaign.
	 *
	 * @since 0.1.0
	 *
	 * @param Campaign $campaign The campaign to create.
	 *
	 * @throws CampaignRepositoryExceptionInterface When the repository insert fails.
	 */
	public function create_campaign( Campaign $campaign ): void {

		$campaign_id = $campaign->get_id();
		$campaign_entity_id = $campaign->get_entity_id();
		$action = CampaignSaveLogAction::Create;

		// @infection-ignore-all
		$this->logger->log_save_started( $campaign_id, $action );

		$this->insert_or_fail( $campaign, $action );
		$this->publish_saved_event_or_log( $campaign_entity_id, $action );

		// @infection-ignore-all
		$this->logger->log_save_succeeded( $campaign_id, $action );
	}

	// phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
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
	): Campaign {

		$log_id = '[new]';
		$action = CampaignSaveLogAction::Create;

		// @infection-ignore-all
		$this->logger->log_save_started( $log_id, $action );

		$assigned_id = $this->insert_without_id_or_fail(
			title: $title,
			is_active: $is_active,
			is_open: $is_open,
			target: $target,
			action: $action,
			log_id: $log_id,
		);

		$campaign = new Campaign(
			id: $assigned_id,
			title: $title,
			is_active: $is_active,
			is_open: $is_open,
			target: $target,
		);

		$this->publish_saved_event_or_log( $assigned_id, $action );

		// @infection-ignore-all
		$this->logger->log_save_succeeded( $assigned_id->get_value(), $action );

		return $campaign;
	}
	// phpcs:enable

	/**
	 * Updates an existing campaign.
	 *
	 * @since 0.1.0
	 *
	 * @param Campaign $campaign The campaign to update.
	 *
	 * @throws CampaignRepositoryExceptionInterface When the repository update fails.
	 */
	public function update_campaign( Campaign $campaign ): void {

		$campaign_id = $campaign->get_id();
		$campaign_entity_id = $campaign->get_entity_id();
		$action = CampaignSaveLogAction::Update;

		// @infection-ignore-all
		$this->logger->log_save_started( $campaign_id, $action );

		$this->update_or_fail( $campaign, $action );
		$this->publish_saved_event_or_log( $campaign_entity_id, $action );

		// @infection-ignore-all
		$this->logger->log_save_succeeded( $campaign_id, $action );
	}

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
	public function save_campaign( Campaign $campaign ): void {

		$campaign_id = $campaign->get_id();
		$campaign_entity_id = $campaign->get_entity_id();
		$action = CampaignSaveLogAction::Save;

		// @infection-ignore-all
		$this->logger->log_save_started( $campaign_id, $action );

		$result = $this->save_or_fail( $campaign, $action );

		$action = match ( $result ) {
			CampaignRepositorySaveResult::Inserted => CampaignSaveLogAction::Create,
			CampaignRepositorySaveResult::Updated => CampaignSaveLogAction::Update,
		};

		$this->publish_saved_event_or_log( $campaign_entity_id, $action );

		// @infection-ignore-all
		$this->logger->log_save_succeeded( $campaign_id, $action );
	}

	/**
	 * Deletes a campaign by its ID.
	 *
	 * @since 0.1.0
	 *
	 * @param EntityId $id The ID of the campaign to delete.
	 *
	 * @throws CampaignRepositoryExceptionInterface When the repository delete fails.
	 */
	public function delete_campaign( EntityId $id ): void {

		$id_value = $id->get_value();

		// @infection-ignore-all
		$this->logger->log_delete_started( $id_value );

		$this->delete_or_fail( $id );
		$this->publish_deleted_event_or_log( $id );

		// @infection-ignore-all
		$this->logger->log_delete_succeeded( $id_value );
	}

	/**
	 * Executes repository insert and rethrows repository errors.
	 *
	 * @since 0.1.0
	 *
	 * @param Campaign $campaign The campaign to insert.
	 * @param CampaignSaveLogAction $action The action for repository failure log.
	 */
	private function insert_or_fail( Campaign $campaign, CampaignSaveLogAction $action ): void {

		try {
			$this->repository->insert( $campaign );
		} catch ( CampaignRepositoryExceptionInterface $e ) {

			$this->logger->log_save_failed_repository( $campaign->get_id(), $e, $action );
			throw $e;
		}
	}

	/**
	 * Executes repository insert_without_id and rethrows repository errors.
	 *
	 * @since 0.1.0
	 *
	 * @param CampaignTitle $title The validated campaign title.
	 * @param bool $is_active Whether the campaign is active.
	 * @param bool $is_open Whether the campaign is open for donations.
	 * @param CampaignTarget $target The validated campaign target.
	 * @param CampaignSaveLogAction $action The action used for failure logging context.
	 * @param int|string $log_id The temporary log identifier (e.g., "[new]").
	 *
	 * @return EntityId The assigned campaign ID returned by the repository.
	 */
	private function insert_without_id_or_fail(
		CampaignTitle $title,
		bool $is_active,
		bool $is_open,
		CampaignTarget $target,
		CampaignSaveLogAction $action,
		int|string $log_id,
	): EntityId {

		try {

			return $this->repository->insert_without_id(
				title: $title,
				is_active: $is_active,
				is_open: $is_open,
				target: $target,
			);
		} catch ( CampaignRepositoryExceptionInterface $e ) {

			$this->logger->log_save_failed_repository( $log_id, $e, $action );
			throw $e;
		}
	}

	/**
	 * Executes repository update and rethrows repository errors.
	 *
	 * @since 0.1.0
	 *
	 * @param Campaign $campaign The campaign to update.
	 * @param CampaignSaveLogAction $action The action for repository failure log.
	 */
	private function update_or_fail( Campaign $campaign, CampaignSaveLogAction $action ): void {

		try {
			$this->repository->update( $campaign );
		} catch ( CampaignRepositoryExceptionInterface $e ) {

			$this->logger->log_save_failed_repository( $campaign->get_id(), $e, $action );
			throw $e;
		}
	}

	/**
	 * Executes repository save and rethrows repository errors.
	 *
	 * @since 0.1.0
	 *
	 * @param Campaign $campaign The campaign to save.
	 * @param CampaignSaveLogAction $action The action for repository failure log.
	 *
	 * @return CampaignRepositorySaveResult The repository outcome (Inserted or Updated).
	 */
	private function save_or_fail( Campaign $campaign, CampaignSaveLogAction $action ): CampaignRepositorySaveResult {

		try {
			return $this->repository->save( $campaign );
		} catch ( CampaignRepositoryExceptionInterface $e ) {

			$this->logger->log_save_failed_repository( $campaign->get_id(), $e, $action );
			throw $e;
		}
	}

	/**
	 * Executes repository delete and rethrows repository errors.
	 *
	 * @since 0.1.0
	 *
	 * @param EntityId $id The ID to delete.
	 */
	private function delete_or_fail( EntityId $id ): void {

		try {
			$this->repository->delete( $id );
		} catch ( CampaignRepositoryExceptionInterface $e ) {

			$this->logger->log_delete_failed_repository( $id->get_value(), $e );
			throw $e;
		}
	}

	/**
	 * Publishes created/updated event and logs publication errors without throwing.
	 *
	 * @since 0.1.0
	 *
	 * @param EntityId $entity_id The campaign entity ID to include in the event.
	 * @param CampaignSaveLogAction $action The action that determines which event to publish.
	 */
	private function publish_saved_event_or_log( EntityId $entity_id, CampaignSaveLogAction $action ): void {

		$event = match ( $action ) {
			CampaignSaveLogAction::Create => new CampaignCreatedEvent( $entity_id ),
			CampaignSaveLogAction::Update => new CampaignUpdatedEvent( $entity_id ),
			// @codeCoverageIgnoreStart
			CampaignSaveLogAction::Save => throw new LogicException(
				'Cannot publish saved event: action must be Create or Update.',
			),
			// @codeCoverageIgnoreEnd
		};

		try {
			$this->event_bus->publish( $event );
		} catch ( Throwable $e ) {

			$this->logger->log_publish_saved_event_failed( $entity_id->get_value(), $e, $action );
		}
	}

	/**
	 * Publishes deleted event and logs publication errors without throwing.
	 *
	 * @since 0.1.0
	 *
	 * @param EntityId $id The ID included in the deleted event.
	 */
	private function publish_deleted_event_or_log( EntityId $id ): void {

		try {
			$this->event_bus->publish( new CampaignDeletedEvent( $id ) );
		} catch ( Throwable $e ) {
			$this->logger->log_publish_deleted_event_failed( $id->get_value(), $e );
		}
	}
}
