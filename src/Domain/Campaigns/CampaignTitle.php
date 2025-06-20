<?php

declare(strict_types=1);

namespace Fundrik\Core\Domain\Campaigns;

use Fundrik\Core\Domain\Campaigns\Exceptions\InvalidCampaignTitleException;

/**
 * Represents the campaign's title.
 *
 * This class ensures that the title is not empty or whitespace only.
 *
 * @since 1.0.0
 */
final readonly class CampaignTitle {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value The campaign title.
	 */
	private function __construct(
		public string $value,
	) {}

	/**
	 * Factory method to create a CampaignTitle instance.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value The campaign title.
	 *
	 * @return self A new instance of CampaignTitle.
	 *
	 * @throws InvalidCampaignTitleException If the title is empty or whitespace.
	 */
	public static function create( string $value ): self {

		$value = trim( $value );

		if ( '' === $value ) {
			throw new InvalidCampaignTitleException( 'Campaign title cannot be empty or whitespace.' );
		}

		return new self( $value );
	}
}
