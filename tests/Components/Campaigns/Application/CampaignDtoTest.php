<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Components\Campaigns\Application;

use Fundrik\Core\Components\Campaigns\Application\CampaignDto;
use Fundrik\Core\Tests\FundrikTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass( CampaignDto::class )]
final class CampaignDtoTest extends FundrikTestCase {

	#[Test]
	public function dto_holds_all_expected_values(): void {

		$dto = $this->make_campaign_dto();

		$this->assertSame( 1, $dto->id );
		$this->assertSame( 'Test Campaign', $dto->title );
		$this->assertTrue( $dto->is_active );
		$this->assertTrue( $dto->is_open );
		$this->assertTrue( $dto->has_target );
		$this->assertSame( 100, $dto->target_amount );
	}

	#[Test]
	public function dto_accepts_uuid_as_id(): void {

		$uuid = '9b34f4ac-94a2-4eab-b729-7f48e97b8f19';

		$dto = $this->make_campaign_dto( id: $uuid );

		$this->assertSame( $uuid, $dto->id );
	}
}
