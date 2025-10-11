<?php

declare(strict_types=1);

// phpcs:disable FundrikStandard.Commenting.SinceTagRequired.MissingSince
// phpcs:disable Squiz.Commenting.ClassComment.Missing
// phpcs:disable Squiz.Commenting.FunctionComment.Missing
// phpcs:disable Generic.Commenting.DocComment.MissingShort
// phpcs:disable FundrikStandard.Classes.FinalClassMustBeReadonly.FinalClassNotReadonly

namespace Fundrik\Core\Examples\Infrastructure;

use Fundrik\Core\Components\Campaigns\Application\CampaignDto;
use Fundrik\Core\Components\Campaigns\Application\CampaignDtoFactory;
use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositoryPort;
use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositorySaveResult;
use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTarget;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTitle;
use Fundrik\Core\Components\Shared\Domain\EntityId;

final class InMemoryCampaignRepository implements CampaignRepositoryPort {

	/** @var array<string, CampaignDto> */
	private array $storage = [];

	public function __construct(
		private readonly CampaignDtoFactory $dto_factory,
	) {}

	public function find_by_id( EntityId $id ): ?CampaignDto {

		$key = $id->get_value();

		return $this->storage[ $key ] ?? null;
	}

	/** @return array<CampaignDto> */
	public function find_all(): array {

		return array_values( $this->storage );
	}

	public function exists( Campaign $campaign ): bool {

		$key = $campaign->get_id();

		return isset( $this->storage[ $key ] );
	}

	public function insert( Campaign $campaign ): void {

		$key = $campaign->get_id();

		if ( isset( $this->storage[ $key ] ) ) {

			throw new ExampleCampaignRepositoryException(
				"Cannot insert campaign: campaign with the same ID already exists. Given: {$key}.",
			);
		}

		$this->storage[ $key ] = $this->dto_factory->from_campaign( $campaign );
	}

	public function insert_without_id(
		CampaignTitle $title,
		bool $is_active,
		bool $is_open,
		CampaignTarget $target,
	): EntityId {

		$id = EntityId::create( random_int( 1, 999 ) );

		$dto = $this->dto_factory->from_array(
			[
				'id' => $id->get_value(),
				'title' => $title->get_value(),
				'is_active' => $is_active,
				'is_open' => $is_open,
				'has_target' => $target->is_enabled(),
				'target_amount' => $target->get_amount(),
			],
		);

		$this->storage[ $dto->id ] = $dto;

		return $id;
	}

	public function update( Campaign $campaign ): void {

		$key = $campaign->get_id();

		if ( ! isset( $this->storage[ $key ] ) ) {

			throw new ExampleCampaignRepositoryException(
				"Cannot update campaign: campaign with the given ID does not exist. Given: {$key}.",
			);
		}

		$this->storage[ $key ] = $this->dto_factory->from_campaign( $campaign );
	}

	public function save( Campaign $campaign ): CampaignRepositorySaveResult {

		$key = $campaign->get_id();
		$dto = $this->dto_factory->from_campaign( $campaign );

		$result = isset( $this->storage[ $key ] )
			? CampaignRepositorySaveResult::Updated
			: CampaignRepositorySaveResult::Inserted;

		$this->storage[ $key ] = $dto;

		return $result;
	}

	public function delete( EntityId $id ): void {

		$key = $id->get_value();

		if ( ! isset( $this->storage[ $key ] ) ) {

			throw new ExampleCampaignRepositoryException(
				"Cannot delete campaign: campaign with the given ID does not exist. Given: {$key}.",
			);
		}

		unset( $this->storage[ $key ] );
	}
}

// phpcs:enable