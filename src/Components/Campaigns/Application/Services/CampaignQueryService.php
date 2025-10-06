<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application\Services;

use Fundrik\Core\Components\Campaigns\Application\CampaignAssembler;
use Fundrik\Core\Components\Campaigns\Application\CampaignDto;
use Fundrik\Core\Components\Campaigns\Application\Exceptions\CampaignAssemblerException;
use Fundrik\Core\Components\Campaigns\Application\Loggers\CampaignQueryServiceLogger;
use Fundrik\Core\Components\Campaigns\Application\Ports\In\CampaignQueryServicePort;
use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositoryExceptionInterface;
use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositoryPort;
use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Components\Shared\Domain\EntityId;

/**
 * Provides application-level queries for retrieving campaigns.
 *
 * @since 0.1.0
 */
final readonly class CampaignQueryService implements CampaignQueryServicePort {

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param CampaignAssembler $assembler Converts between DTOs and domain entities.
	 * @param CampaignRepositoryPort $repository Provides access to campaign storage.
	 * @param CampaignQueryServiceLogger $logger Logs application-level operations and outcomes.
	 */
	public function __construct(
		private CampaignAssembler $assembler,
		private CampaignRepositoryPort $repository,
		private CampaignQueryServiceLogger $logger,
	) {}

	/**
	 * Finds a campaign by its ID.
	 *
	 * @since 0.1.0
	 *
	 * @param EntityId $id The ID of the campaign to retrieve.
	 *
	 * @return Campaign|null The campaign if found, otherwise null.
	 *
	 * @throws CampaignRepositoryExceptionInterface When the repository lookup fails.
	 * @throws CampaignAssemblerException When the DTO cannot be converted into a Campaign.
	 */
	public function find_campaign_by_id( EntityId $id ): ?Campaign {

		// @infection-ignore-all
		$this->logger->log_find_by_id_started( $id->get_value() );

		$campaign_dto = $this->find_dto_or_null( $id );

		if ( $campaign_dto === null ) {
			// @infection-ignore-all
			$this->logger->log_find_by_id_not_found( $id->get_value() );
			return null;
		}

		$campaign = $this->assemble_campaign_or_fail( $campaign_dto );

		// @infection-ignore-all
		$this->logger->log_find_by_id_succeeded( $id->get_value() );

		return $campaign;
	}

	/**
	 * Retrieves all campaigns.
	 *
	 * @since 0.1.0
	 *
	 * @return array<Campaign> All available campaign entities.
	 *
	 * @throws CampaignRepositoryExceptionInterface When the repository lookup fails.
	 * @throws CampaignAssemblerException When a DTO cannot be converted into a Campaign.
	 */
	public function find_all_campaigns(): array {

		// @infection-ignore-all
		$this->logger->log_find_all_started();

		$dto_list = $this->find_all_dtos();
		$entities = $this->assemble_campaigns_or_fail( $dto_list );

		// @infection-ignore-all
		$this->logger->log_find_all_succeeded( count( $entities ) );

		return $entities;
	}

	/**
	 * Reads a campaign DTO by ID from the repository and rethrows repository errors.
	 *
	 * @since 0.1.0
	 *
	 * @param EntityId $id The campaign ID to search.
	 *
	 * @return CampaignDto|null The DTO if found, otherwise null.
	 */
	private function find_dto_or_null( EntityId $id ): ?CampaignDto {

		try {
			return $this->repository->find_by_id( $id );
		} catch ( CampaignRepositoryExceptionInterface $e ) {

			$this->logger->log_find_by_id_failed_repository( $id->get_value(), $e );
			throw $e;
		}
	}

	/**
	 * Reads all campaign DTOs from the repository and rethrows repository errors.
	 *
	 * @since 0.1.0
	 *
	 * @return array<CampaignDto> The list of campaign DTOs.
	 */
	private function find_all_dtos(): array {

		try {
			return $this->repository->find_all();
		} catch ( CampaignRepositoryExceptionInterface $e ) {

			$this->logger->log_find_all_failed_repository( $e );
			throw $e;
		}
	}

	/**
	 * Assembles a campaign entity from DTO and rethrows assembly errors.
	 *
	 * @since 0.1.0
	 *
	 * @param CampaignDto $dto The DTO to convert.
	 *
	 * @return Campaign The assembled campaign entity.
	 */
	private function assemble_campaign_or_fail( CampaignDto $dto ): Campaign {

		try {
			return $this->assembler->from_dto( $dto );
		} catch ( CampaignAssemblerException $e ) {

			$this->logger->log_find_by_id_failed_assembler( $dto->id, $e );
			throw $e;
		}
	}

	/**
	 * Assembles campaign entities from a list of DTOs and rethrows assembly errors.
	 *
	 * @since 0.1.0
	 *
	 * @param array<CampaignDto> $dto_list The list of DTOs to convert.
	 *
	 * @return array<Campaign> The assembled campaign entities.
	 */
	private function assemble_campaigns_or_fail( array $dto_list ): array {

		try {
			return array_map(
				fn ( CampaignDto $dto ): Campaign => $this->assembler->from_dto( $dto ),
				$dto_list,
			);
		} catch ( CampaignAssemblerException $e ) {

			$this->logger->log_find_all_failed_assembler( $e );
			throw $e;
		}
	}
}
