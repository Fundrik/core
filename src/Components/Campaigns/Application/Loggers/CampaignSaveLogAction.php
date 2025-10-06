<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application\Loggers;

/**
 * Enumerates allowed save actions for Campaign logs.
 *
 * @since 0.1.0
 */
enum CampaignSaveLogAction: string {

	case Create = 'create';
	case Update = 'update';
	case Save = 'save';
}
