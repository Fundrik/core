<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain\Campaigns;

use Fundrik\Core\Domain\Campaigns\Exceptions\InvalidCampaignTitleException;

/**
 * Value Object representing campaign title.
 *
 * Ensures non-empty, trimmed titles.
 *
 * @since 1.0.0
 */
final readonly class CampaignTitle {

	/**
	 * Private constructor, use factory method.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value Campaign title.
	 */
	private function __construct(
		public string $value,
	) {}

	/**
	 * Factory method to create CampaignTitle.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value Title string.
	 *
	 * @return self New CampaignTitle instance.
	 */
	public static function create( string $value ): self {

		$value = trim( $value );

		if ( $value === '' ) {
			throw new InvalidCampaignTitleException( 'Campaign title cannot be empty or whitespace.' );
		}

		return new self( $value );
	}
}
