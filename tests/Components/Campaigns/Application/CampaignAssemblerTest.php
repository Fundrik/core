<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Components\Campaigns\Application;

use Fundrik\Core\Components\Campaigns\Application\CampaignAssembler;
use Fundrik\Core\Components\Campaigns\Application\CampaignDto;
use Fundrik\Core\Components\Campaigns\Application\Exceptions\CampaignAssemblerException;
use Fundrik\Core\Components\Campaigns\Domain\Campaign;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTarget;
use Fundrik\Core\Components\Campaigns\Domain\CampaignTitle;
use Fundrik\Core\Components\Shared\Domain\EntityId;
use Fundrik\Core\Support\TypeCaster;
use Fundrik\Core\Tests\FundrikTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass( CampaignAssembler::class )]
#[UsesClass( Campaign::class )]
#[UsesClass( CampaignDto::class )]
#[UsesClass( CampaignTarget::class )]
#[UsesClass( CampaignTitle::class )]
#[UsesClass( EntityId::class )]
#[UsesClass( TypeCaster::class )]
final class CampaignAssemblerTest extends FundrikTestCase {

	private CampaignAssembler $assembler;

	protected function setUp(): void {

		$this->assembler = new CampaignAssembler();
	}

	#[Test]
	public function it_creates_campaign_from_valid_dto(): void {

		$dto = $this->make_campaign_dto();
		$campaign = $this->assembler->from_dto( $dto );

		$this->assertInstanceOf( Campaign::class, $campaign );
		$this->assert_campaign_equals_dto( $campaign, $dto );
	}

	#[Test]
	public function it_throws_on_invalid_id(): void {

		$this->expectException( CampaignAssemblerException::class );

		$this->expectExceptionMessageMatches( '/^Cannot assemble Campaign from DTO:/' );

		$this->assembler->from_dto(
			$this->make_campaign_dto(
				id: -1,
			),
		);
	}

	#[Test]
	public function it_throws_on_invalid_title(): void {

		$this->expectException( CampaignAssemblerException::class );
		$this->expectExceptionMessageMatches( '/^Cannot assemble Campaign from DTO:/' );

		$this->assembler->from_dto(
			$this->make_campaign_dto(
				title: '   ',
			),
		);
	}

	#[Test]
	public function it_throws_on_invalid_target(): void {

		$this->expectException( CampaignAssemblerException::class );
		$this->expectExceptionMessageMatches( '/^Cannot assemble Campaign from DTO:/' );

		$this->assembler->from_dto(
			$this->make_campaign_dto(
				has_target: true,
				target_amount: 0,
			),
		);
	}
}
