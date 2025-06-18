<?php

declare(strict_types=1);

namespace Fundrik\Core;

use Fundrik\Core\Infrastructure\Interfaces\DependencyProviderInterface;
use Fundrik\Core\Infrastructure\Internal\Container;
use Fundrik\Core\Infrastructure\Internal\ContainerManager;

/**
 * Bootstraps and initializes the core components.
 *
 * @since 1.0.0
 */
final readonly class App {

	/**
	 * Retrieves the Fundrik container instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Container The instance of the Fundrik container.
	 */
	public function container(): Container {

		return ContainerManager::get();
	}

	/**
	 * Registers bindings from a dependency provider into the Fundrik container.
	 *
	 * @since 1.0.0
	 *
	 * @param DependencyProviderInterface $provider The dependency provider.
	 * @param string                      $category Optional category of bindings to register.
	 */
	public function register_bindings( DependencyProviderInterface $provider, string $category = '' ): void {

		$bindings = $provider->get_bindings( $category );

		foreach ( $bindings as $abstract => $concrete ) {

			if ( is_array( $concrete ) ) {

				foreach ( $concrete as $a => $c ) {
					$this->container()->singleton( $a, $c );
				}
			} else {
				$this->container()->singleton( $abstract, $concrete );
			}
		}
	}
}
