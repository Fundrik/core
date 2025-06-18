<?php

declare(strict_types=1);

namespace Fundrik\Core\Infrastructure\Interfaces;

/**
 * Interface for dependency provider classes that supply container bindings.
 *
 * @since 1.0.0
 */
interface DependencyProviderInterface {

	/**
	 * Returns container bindings.
	 *
	 * @param string $category Optional category of bindings to return.
	 *
	 * @return array<string, string|callable|array<string, string|callable>> Bindings array.
	 */
	public function get_bindings( string $category = '' ): array;
}
