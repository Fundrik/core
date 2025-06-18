<?php

declare(strict_types=1);

namespace Fundrik\Core\Infrastructure\Interfaces;

use Closure;

/**
 * Interface for a Dependency Injection Container used within Fundrik.
 *
 * @since 1.0.0
 */
interface ContainerInterface {

	/**
	 * Retrieves a resolved instance of the given class or interface.
	 *
	 * If the binding was registered as a factory or class name, it will be instantiated
	 * according to the container's resolution rules. Throws an exception if the identifier
	 * is not bound and not instantiable.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Class or interface name.
	 *
	 * @return object The resolved instance.
	 *
	 * @throws RuntimeException If the identifier cannot be resolved.
	 */
	public function get( string $id ): object;

	/**
	 * Determines whether the container has a binding for the given identifier.
	 *
	 * This does not guarantee that the binding is instantiable, only that it exists.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Class or interface name.
	 *
	 * @return bool True if the binding exists, false otherwise.
	 */
	public function has( string $id ): bool;

	/**
	 * Registers a singleton binding in the container.
	 *
	 * A singleton binding ensures that only one instance of the class or closure result
	 * will be created and shared for all resolutions.
	 *
	 * If $concrete is:
	 * - `null`: the container will instantiate `$abstract` directly.
	 * - `string`: the container will resolve the given class name when `$abstract` is requested.
	 * - `Closure`: the closure will be executed once and its result reused for subsequent resolutions.
	 *
	 * @since 1.0.0
	 *
	 * @param string              $abstract Class or interface name.
	 * @param Closure|string|null $concrete Optional concrete implementation or factory closure.
	 */
	public function singleton(
		// phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.abstractFound
		string $abstract,
		Closure|string|null $concrete = null
	): void;
}
