<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Application\Events;

use Fundrik\Core\Components\Shared\Domain\EntityId;

/**
 * Signals that a campaign has been deleted.
 *
 * @since 0.1.0
 */
final readonly class CampaignDeletedEvent {

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param EntityId $campaign_id The ID of the deleted campaign.
	 */
	public function __construct(
		public EntityId $campaign_id,
	) {}
}
