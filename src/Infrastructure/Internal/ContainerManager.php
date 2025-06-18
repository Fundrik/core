<?php

declare(strict_types=1);

namespace Fundrik\Core\Infrastructure\Internal;

/**
 * Manages a singleton instance of the Fundrik container.
 *
 * Provides internal access to the DI container.
 *
 * @since 1.0.0
 *
 * @internal
 */
final class ContainerManager {

	/**
	 * The shared container instance.
	 *
	 * @var Container|null
	 */
	private static ?Container $container = null;

	/**
	 * Returns the current container instance.
	 *
	 * If the container has not been set, a new one is created.
	 *
	 * @since 1.0.0
	 *
	 * @return Container The current shared container instance,
	 *                   newly created if it was not previously set.
	 */
	public static function get(): Container {

		if ( null === self::$container ) {

			self::set( new Container() );
		}

		return self::$container;
	}

	/**
	 * Sets the container instance.
	 *
	 * Used in tests to inject a custom container.
	 *
	 * @since 1.0.0
	 *
	 * @param Container|null $container The container to set, or null to clear.
	 */
	public static function set( ?Container $container ): void {

		self::$container = $container;
	}

	/**
	 * Resets the container instance to null.
	 *
	 * Used to clear the container state between tests to ensure isolation
	 * and prevent shared state between tests.
	 *
	 * @since 1.0.0
	 */
	public static function reset(): void {

		self::set( null );
	}

	/**
	 * Creates and sets a new container instance, replacing any existing one.
	 *
	 * Useful in tests to ensure a fresh container state.
	 *
	 * @since 1.0.0
	 *
	 * @return Container The newly created container.
	 */
	public static function get_fresh(): Container {

		self::set( new Container() );

		return self::get();
	}
}
