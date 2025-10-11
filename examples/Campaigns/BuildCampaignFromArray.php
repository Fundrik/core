<?php

declare(strict_types=1);

// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_print_r

namespace Fundrik\Core\Examples\Campaigns;

use Fundrik\Core\Components\Campaigns\Application\CampaignAssembler;
use Fundrik\Core\Components\Campaigns\Application\CampaignDtoFactory;

require __DIR__ . '/../../vendor/autoload.php';

( static function (): void {

	$payload = [
		'id' => 101, // or uuid, e.g. 0199d323-27a5-71cd-a480-25ad215e4faf.
		'title' => 'Clean Water Initiative',
		'is_active' => true,
		'is_open' => true,
		'has_target' => true,
		'target_amount' => 500,
	];

	$dto_factory = new CampaignDtoFactory();
	$assembler = new CampaignAssembler();

	$dto = $dto_factory->from_array( $payload );
	$campaign = $assembler->from_dto( $dto );

	echo '<pre>';
	echo "Campaign built successfully:\n\n";
	print_r( $campaign );
	echo '</pre>';
} )();
