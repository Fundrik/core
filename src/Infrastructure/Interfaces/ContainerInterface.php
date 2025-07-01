<?php

declare(strict_types=1);

namespace Fundrik\Core\Infrastructure\Interfaces;

use Closure;

/**
 * Dependency container interface.
 *
 * Resolves and registers bindings for runtime use.
 *
 * @since 1.0.0
 */
interface ContainerInterface {

	/**
	 * Resolves an instance for the given identifier.
	 *
	 * If the binding is a class name or factory, it will be instantiated or executed accordingly.
	 * Throws an exception if the identifier is not bound and cannot be auto-resolved.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Fully qualified class or interface name.
	 * 
	 * @phpstan-param class-string $id
	 *
	 * @return object Resolved instance.
	 */
	public function get( string $id ): object;

	/**
	 * Creates (makes) an instance of the given class or interface.
	 *
	 * Allows passing parameters to the constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Class or interface name.
	 * @param array<string, mixed> $parameters Optional parameters to pass during instantiation.
	 * 
	 * @phpstan-param class-string $id
	 *
	 * @return object The created instance.
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
	 */
	public function make( string $id, array $parameters = [] ): object;

	/**
	 * Checks if the container has a binding for the given identifier.
	 *
	 * Does not guarantee the binding is instantiable, only that it exists.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Fully qualified class or interface name.
	 *
	 * @phpstan-param class-string<object> $id
	 *
	 * @return bool True if a binding exists, false otherwise.
	 */
	public function has( string $id ): bool;

	/**
	 * Registers a singleton binding.
	 *
	 * A singleton is instantiated once and reused for subsequent resolutions.
	 *
	 * - If `$concrete` is `null`, the container will instantiate `$abstract` directly.
	 * - If `$concrete` is a `string`, it will be resolved as a class when `$abstract` is requested.
	 * - If `$concrete` is a `Closure`, it will be invoked once and the result reused.
	 *
	 * @since 1.0.0
	 *
	 * @param string $abstract Abstract identifier.
	 * @param Closure|string|null $concrete Optional implementation or factory.
	 *
	 * @template TSingleton of object
	 *
	 * @phpstan-param class-string<TSingleton> $abstract
	 * @phpstan-param (Closure(): TSingleton)|class-string<TSingleton>|null $concrete
	 */
	public function singleton(
		// phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.abstractFound
		string $abstract,
		Closure|string|null $concrete = null,
	): void;
}
