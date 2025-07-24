<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Campaigns\Domain;

use Fundrik\Core\Components\Campaigns\Domain\Exceptions\InvalidCampaignTitleException;

/**
 * Represents the title of a fundraising campaign.
 *
 * Validates that the title is non-empty and trimmed.
 *
 * @since 1.0.0
 */
final readonly class CampaignTitle {

	/**
	 * Private constructor, use factory method.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value The validated campaign title.
	 */
	private function __construct(
		public string $value,
	) {}

	/**
	 * Creates a validated title value object.
	 *
	 * Trims the input and throws if it is empty or only whitespace.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value The raw input title.
	 *
	 * @return self The campaign title value object.
	 */
	public static function create( string $value ): self {

		$value = trim( $value );

		if ( $value === '' ) {
			throw new InvalidCampaignTitleException( 'Campaign title cannot be empty or whitespace.' );
		}

		return new self( $value );
	}
}
