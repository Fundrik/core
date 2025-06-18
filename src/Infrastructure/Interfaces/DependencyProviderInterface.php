<?php

declare(strict_types=1);

namespace Fundrik\Core\Infrastructure\Interfaces;

/**
 * Interface for dependency provider classes that supply container bindings.
 *
 * Bindings can be grouped by an optional category, allowing selective retrieval.
 *
 * @since 1.0.0
 */
interface DependencyProviderInterface {

	/**
	 * Returns container bindings.
	 *
	 * @since 1.0.0
	 *
	 * @param string $category Optional category name to filter bindings by group.
	 *
	 * @return array<string, string|callable|array<string, string|callable>> Bindings array.
	 */
	public function get_bindings( string $category = '' ): array;
}
