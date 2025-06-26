<?php

declare(strict_types=1);

namespace Fundrik\Core\Infrastructure\Interfaces;

/**
 * Interface for providing service bindings to the dependency container.
 *
 * Supports optional grouping by category for organized registration
 * and structured dependency resolution.
 *
 * @since 1.0.0
 */
interface DependencyProviderInterface {

	/**
	 * Returns an array of container bindings.
	 *
	 * Each binding maps an abstract identifier to a concrete implementation,
	 * which can be a class name, closure or a nested group of bindings.
	 *
	 * @since 1.0.0
	 *
	 * @param string $category Optional category to filter bindings.
	 *
	 * @return array<string, array<string, string|Closure>>|array<string, Closure|string> Bindings keyed by identifier.
	 */
	public function get_bindings( string $category = '' ): array;
}
