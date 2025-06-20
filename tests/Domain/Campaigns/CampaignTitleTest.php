<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Domain\Campaigns;

use Fundrik\Core\Domain\Campaigns\CampaignTitle;
use Fundrik\Core\Domain\Campaigns\Exceptions\InvalidCampaignTitleException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass( CampaignTitle::class )]
class CampaignTitleTest extends TestCase {

	#[Test]
	public function creates_with_valid_title(): void {

		$title = CampaignTitle::create( 'Save the Rainforest' );

		$this->assertSame( 'Save the Rainforest', $title->value );
	}

	#[Test]
	public function trims_title_before_storing(): void {

		$title = CampaignTitle::create( '  Clean Oceans  ' );

		$this->assertSame( 'Clean Oceans', $title->value );
	}

	#[Test]
	public function throws_when_title_is_empty(): void {

		$this->expectException( InvalidCampaignTitleException::class );
		$this->expectExceptionMessage( 'Campaign title cannot be empty or whitespace.' );

		CampaignTitle::create( '' );
	}

	#[Test]
	public function throws_when_title_is_only_whitespace(): void {

		$this->expectException( InvalidCampaignTitleException::class );
		$this->expectExceptionMessage( 'Campaign title cannot be empty or whitespace.' );

		CampaignTitle::create( '     ' );
	}

	#[Test]
	public function accepts_unicode_and_symbols(): void {

		$title = CampaignTitle::create( '💧 Вода для всех' );

		$this->assertSame( '💧 Вода для всех', $title->value );
	}
}
