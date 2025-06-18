<?php

declare(strict_types=1);

namespace Fundrik\Core\Infrastructure\Internal;

use Illuminate\Container\Container as IlluminateContainer;

/**
 * Fundrik Dependency Injection Container.
 *
 * Extends Laravel Service Container to provide dependency injection functionality
 * inside the Fundrik plugin. Used internally and should not be relied upon externally.
 *
 * @since 1.0.0
 *
 * @internal
 */
final class Container extends IlluminateContainer {

}
