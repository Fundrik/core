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
#[UsesClass( CampaignDto::class )]
#[UsesClass( Campaign::class )]
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
	public function assembles_campaign(): void {

		$dto = $this->make_campaign_dto();
		$campaign = $this->assembler->from_dto( $dto );

		$this->assertInstanceOf( Campaign::class, $campaign );
		$this->assert_campaign_equals_dto( $campaign, $dto );
	}

	#[Test]
	public function throws_when_entity_invariants_are_violated(): void {

		$this->expectException( CampaignAssemblerException::class );
		$this->expectExceptionMessageMatches( '/Failed to assemble Campaign from DTO: /' );

		$this->assembler->from_dto(
			$this->make_campaign_dto(
				target_amount: 0, // Targeting is enabled, but amount is zero — violates domain invariant.
			),
		);
	}
}
