<?php

declare(strict_types=1);

namespace Fundrik\Core\Components\Shared\Application\Ports\Out;

/**
 * Provides the outbound port for publishing application events.
 *
 * Keeps the application layer decoupled from the underlying event dispatcher implementation.
 *
 * @since 0.1.0
 */
interface EventBusPort {

	/**
	 * Publishes the given event to all registered listeners.
	 *
	 * @since 0.1.0
	 *
	 * @param object $event The event object to publish.
	 */
	public function publish( object $event ): void;
}
