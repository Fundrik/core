<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests;

use Fundrik\Core\Components\Campaigns\Application\CampaignDto;
use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTarget;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTitle;
use Fundrik\Core\Components\Shared\Domain\EntityId;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class FundrikTestCase extends PHPUnitTestCase {

	protected function assert_campaign_equals_dto( Campaign $campaign, CampaignDto $dto ): void {

		$this->assertSame( $campaign->get_id(), $dto->id );
		$this->assertSame( $campaign->get_title(), $dto->title );
		$this->assertSame( $campaign->is_active(), $dto->is_active );
		$this->assertSame( $campaign->is_open(), $dto->is_open );
		$this->assertSame( $campaign->has_target(), $dto->has_target );
		$this->assertSame( $campaign->get_target_amount(), $dto->target_amount );
	}

	/**
	 * Returns a valid CampaignDto for use in tests.
	 * Allows overriding fields to simulate variations.
	 */
	protected function make_campaign_dto(
		int|string $id = 1,
		string $title = 'Test Campaign',
		bool $is_active = true,
		bool $is_open = true,
		bool $has_target = true,
		int $target_amount = 100,
	): CampaignDto {

		return new CampaignDto( $id, $title, $is_active, $is_open, $has_target, $target_amount );
	}

	/**
	 * Returns a invalid CampaignDto for use in tests.
	 */
	protected function make_invalid_campaign_dto(): CampaignDto {

		return $this->make_campaign_dto( id: -1 );
	}

	/**
	 * Returns a valid Campaign for use in tests.
	 * Allows overriding fields to simulate variations.
	 */
	protected function make_campaign(
		int|string $id = 1,
		string $title = 'Test Campaign',
		bool $is_active = true,
		bool $is_open = true,
		bool $has_target = true,
		int $target_amount = 100,
	): Campaign {

		return new Campaign(
			id: EntityId::create( $id ),
			title: CampaignTitle::create( $title ),
			is_active: $is_active,
			is_open: $is_open,
			target: CampaignTarget::create( $has_target, $target_amount ),
		);
	}
}
