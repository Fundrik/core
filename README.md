# Fundrik Core

*Domain-driven PHP library for building transparent fundraising systems.*

![License](https://img.shields.io/github/license/Fundrik/core)
![Packagist](https://img.shields.io/packagist/v/fundrik/core)
![PHP Version](https://img.shields.io/badge/PHP-8.2+-blue)
![CodeStyle](https://img.shields.io/badge/Code%20Style-FundrikStandard-blueviolet)

![PHPStan](https://img.shields.io/badge/PHPStan-level%2010-brightgreen)
![PHPUnit](https://img.shields.io/badge/PHPUnit-100%25%20coverage-brightgreen)
![Infection](https://img.shields.io/badge/Infection-100%25%20killed-brightgreen)
![Deptrac](https://img.shields.io/badge/Deptrac-100%25%20allowed-brightgreen)

---

## Table of Contents

- [Overview](#overview)
- [Quick Start](#quick-start)
- [Architecture](#architecture)
- [Public API](#public-api)
- [License](#license)

## Overview

Building fundraising platforms requires the same level of precision and reliability as any e-commerce system.

**Fundrik Core** provides a predictable, well-documented, and fully testable foundation for developing such systems with confidence.

### Key Features

- **Strict Typing** — explicit type declarations ensure safety and prevent PHP’s implicit type juggling for predictable behavior.
- **Explicit Architecture** — clear separation of Domain, Application, and Infrastructure layers to prevent dependency leaks and maintain long-term stability.
- **Rich Domain Model** — entities and value objects encapsulate all invariants and rules to ensure domain integrity and expressive business logic.
- **Immutable Design** — readonly entities, value objects, and DTOs guarantee consistent state and predictable transformations.
- **Application Ports & Services** — ports define boundaries between layers, while services coordinate use cases in a clear and testable way.
- **Structured Logging & Traceability** — unified PSR-3 logging with contextual payloads makes every operation and failure observable.
- **Transparent Event Handling** — structured application events expose system actions for monitoring and investigation.
- **Strict Type Casting** — centralized utilities ensure safe, predictable, and explicit value normalization.
- **Layered Exception Hierarchy** — consistent exception contracts per layer maintain clear error boundaries and recovery paths.
- **Framework Independence** — pure PHP core with no framework dependencies for maximum portability.

## Quick Start

### 1. Install Fundrik Core

```bash
composer require fundrik/core
```

### 2. Implement ports in your app

Fundrik Core ships framework-agnostic ports (interfaces). To use it in a real app, implement these contracts in your infrastructure layer:

- **CampaignRepositoryPort** — provides persistence for campaigns (find, insert, update, save, delete).
- **CampaignRepositoryExceptionInterface** — marks all exceptions thrown by your repository implementation.
- **EventBusPort** — publishes application events (to your bus, queue, or listeners).
- **Psr\Log\LoggerInterface** — standard PSR-3 logger (Monolog, your adapter, etc).

> Tip: For a quick local try-out, see lightweight test implementations under [`examples/Infrastructure/`](./examples/Infrastructure/). They are not for production, but useful to start.

### 3. Use it!

There are two simple ways to start using Fundrik Core services:

1. **Manual wiring** — directly instantiate classes and inject dependencies yourself.
2. **Using a container** — let your favorite DI container (like Laravel's Service Container) resolve dependencies automatically.

Below are runnable examples of both approaches.

#### Manual wiring (basic example)

The example below shows how to get a campaign by ID using the `CampaignQueryService` directly:

```php
declare(strict_types=1);

use Fundrik\Core\Components\Campaigns\Application\CampaignAssembler;
use Fundrik\Core\Components\Campaigns\Application\CampaignDtoFactory;
use Fundrik\Core\Components\Campaigns\Application\Loggers\CampaignQueryServiceLogger;
use Fundrik\Core\Components\Campaigns\Application\Services\CampaignQueryService;
use Fundrik\Core\Components\Shared\Domain\EntityId;
use Fundrik\Core\Examples\Infrastructure\EchoLogger;
use Fundrik\Core\Examples\Infrastructure\InMemoryCampaignRepository;

require __DIR__ . '/../../vendor/autoload.php';

$psr_logger = new EchoLogger(); // replace with your PSR-3 logger.
$dto_factory = new CampaignDtoFactory();

$assembler = new CampaignAssembler();
$repository = new InMemoryCampaignRepository( $dto_factory ); // replace with your real repository.
$query_logger = new CampaignQueryServiceLogger( $psr_logger );

$query_service = new CampaignQueryService( $assembler, $repository, $query_logger );

$id = EntityId::create( 123 );

$campaign = $query_service->find_campaign_by_id( $id );

if ( $campaign === null ) {
	echo "Campaign not found.\n";
} else {
	echo "ID={$campaign->get_id()} Title={$campaign->get_title()}\n";
	echo 'Active=' . ( $campaign->is_active() ? 'yes' : 'no' ) . "\n";
	echo 'Open=' . ( $campaign->is_open() ? 'yes' : 'no' ) . "\n";
	echo 'Target=' . ( $campaign->has_target() ? $campaign->get_target_amount() : 'no' ) . "\n";
}
```

#### Using a container (Laravel Service Container)

If you prefer dependency injection, you can register your infrastructure classes in any DI container.  
Below is an example using Laravel’s Service Container, but the same idea works with Symfony, PHP-DI, or others.

> Optional dependency:
>
> ```bash
> composer require illuminate/container
> ```

```php
declare(strict_types=1);

use Fundrik\Core\Components\Campaigns\Application\Ports\Out\CampaignRepositoryPort;
use Fundrik\Core\Components\Campaigns\Application\Services\CampaignQueryService;
use Fundrik\Core\Components\Shared\Domain\EntityId;
use Fundrik\Core\Examples\Infrastructure\EchoLogger;
use Fundrik\Core\Examples\Infrastructure\InMemoryCampaignRepository;
use Illuminate\Container\Container;
use Psr\Log\LoggerInterface;

require __DIR__ . '/../../vendor/autoload.php';

$container = new Container();
$container->singleton( LoggerInterface::class, EchoLogger::class ); // replace with your PSR-3 logger.
$container->singleton( CampaignRepositoryPort::class, InMemoryCampaignRepository::class ); // replace with your real repository.

$query_service = $container->make( CampaignQueryService::class );

$id = EntityId::create( 123 );

$campaign = $query_service->find_campaign_by_id( $id );

if ( $campaign === null ) {
	echo "Campaign not found.\n";
} else {
	echo "ID={$campaign->get_id()} Title={$campaign->get_title()}\n";
	echo 'Active=' . ( $campaign->is_active() ? 'yes' : 'no' ) . "\n";
	echo 'Open=' . ( $campaign->is_open() ? 'yes' : 'no' ) . "\n";
	echo 'Target=' . ( $campaign->has_target() ? $campaign->get_target_amount() : 'no' ) . "\n";
}
```

For more runnable scenarios, check the [`examples/`](./examples/) directory.

## Architecture

Fundrik Core follows a **modular, layered structure** that cleanly separates business logic from technical implementation.

The project is organized into three logical areas: **Components**, **Infrastructure**, and **Support**.

### Components — Business logic and use cases

Each component is an isolated unit that contains its **own business rules (Domain)** and **use cases (Application)** — but **no infrastructure code**.

Use cases are implemented as **application services** that orchestrate domain behavior through well-defined ports.

```
src/
└── Components/
    ├── Campaigns/
    │   ├── Domain/
    │   └── Application/
    └── Shared/
```

| Folder | Purpose |
|---------|----------|
| **Domain** | Encapsulates business rules via entities and value objects. |
| **Application** | Implements use cases (as services) and defines inbound/outbound ports. |
| **Shared** | Contains elements reused across components (e.g. `EntityId`). |

Components are **framework-independent** — they know nothing about persistence, logging, or delivery mechanisms.  
This makes them reusable and testable in any environment.

### Infrastructure — Technical modules

Infrastructure lives **alongside Components**, not inside them.  
It provides **technical modules** — such as container bindings, logging adapters, database repositories, or platform integrations.

```
src/
└── Infrastructure/
    ├── Container/ ← dependency injection setup
    ├── Logger/    ← PSR-3 logger adapters
    ├── Database/  ← repository implementations
    ├── WordPress/ ← integration layer for WP platform
    └── ...        ← other technical modules as needed
```

Infrastructure modules **implement the ports** defined by Components, allowing the same business logic to run on different platforms.

> Example layout only — the actual set of modules depends on your project needs.

### Support — shared technical utilities

The `Support/` directory provides framework-agnostic helper classes and utilities used across the core.  
It contains reusable building blocks that assist both **Components** and **Infrastructure**, but do not belong to any specific domain.

```
src/
└── Support/
    ├── TypeCaster.php     ← strict type conversions and validations
    └── ArrayExtractor.php ← safe array value extraction with type checks
```

### Design Principles

- **Strict typing** — explicit type declarations for reliability.  
- **Immutable objects** — all entities, DTOs, and value objects are `readonly`.  
- **Interface boundaries** — Components depend only on contracts, not implementations.  
- **No framework dependencies** — runs anywhere PHP 8.2+ is available.  
- **Traceability** — structured logging and events make system behavior observable.  
- **Modularity** — Components isolate domain concerns; Infrastructure isolates technical ones.

> For runnable examples showing how Components and Infrastructure interact,  
> see the [`examples/`](./examples/) directory.

### In short:
Fundrik Core separates *what the system does* (**Components**)  
from *how it runs* (**Infrastructure**) —  
keeping business logic clean, testable, and independent from technology.

## Public API

### Entities

Entities in Fundrik Core are **immutable**, meaning every domain action (such as `rename` or `activate`) returns a **new entity instance** with an updated state, while the original remains unchanged.

Invalid state transitions are not allowed — for example, attempting to activate an already active campaign throws a `CampaignChangeException`.

Entities are responsible **only for maintaining and validating their internal state**. They do not handle persistence or external coordination.  
To find, create, update, or delete entities in storage, use the **application services** (use cases) provided in the `Application` layer.

#### EntityId

`EntityId` represents a **cross-component domain identifier**. It can hold a positive integer or a valid UUID. 

```php
$int_id = EntityId::create( 200 );
$uuid_id = EntityId::create( '0199d323-27a5-71cd-a480-25ad215e4faf' );

$int_id->get_value();  // 200
$uuid_id->get_value(); // 0199d323-27a5-71cd-a480-25ad215e4faf

$another_int_id = EntityId::create( 200 );
$int_id->equals( $another_int_id ); // true
```

#### Campaign

```php
$campaign = new Campaign(
	id: EntityId::create( 123 ),
	title: CampaignTitle::create( 'Save the Whales' ),
	is_active: true,
	is_open: false,
	target: CampaignTarget::create( true, 100 ),
);

$updated = $campaign
	->rename( 'Save the Birds' )
	->deactivate()
	->open()
	->set_target_amount( 999 );

$campaign->get_id();            // 123
$campaign->get_title();         // Save the Whales
$campaign->is_active();         // true
$campaign->is_open();           // false
$campaign->has_target();        // true
$campaign->get_target_amount(); // 100

$updated->get_id();             // 123
$updated->get_title();          // Save the Birds
$updated->is_active();          // false
$updated->is_open();            // true
$updated->has_target();         // true
$updated->get_target_amount();  // 999
```

You can also build a `Campaign` from an associative array:

```php
$payload = [
	'id' => 101, // or UUID, e.g. 0199d323-27a5-71cd-a480-25ad215e4faf.
	'title' => 'Clean Water Initiative',
	'is_active' => true,
	'is_open' => true,
	'has_target' => true,
	'target_amount' => 500,
];

$dto_factory = new CampaignDtoFactory();
$assembler = new CampaignAssembler();

$dto = $dto_factory->from_array( $payload );
$campaign = $assembler->from_dto( $dto );

$campaign->get_title(); // Clean Water Initiative
```

See [`examples/Campaigns/BuildCampaignFromArray.php`](./examples/Campaigns/BuildCampaignFromArray.php) for the full example.

### Application Services

Application services define the **use cases** of the system.  
They coordinate domain behavior, interact with repositories, and publish application events.

#### CampaignQueryService

`CampaignQueryService` provides **read-only operations** for retrieving campaigns.

```php
$campaign = $query_service->find_campaign_by_id( EntityId::create( 123 ) ); // Campaign instance or null if no campaign is found
$all = $query_service->find_all_campaigns(); // List of Campaign instances (may be empty)
```

See [`examples/Campaigns/FindCampaignById.php`](./examples/Campaigns/FindCampaignById.php) and [`examples/Campaigns/FindAllCampaigns.php`](./examples/Campaigns/FindAllCampaigns.php) for detailed examples.

#### CampaignCommandService

`CampaignCommandService` handles **write operations** — creating, updating, saving, and deleting campaigns.

```php
// Create a new campaign
$new = $command_service->create_campaign_without_id( // or use create_campaign method if you have built campaign with id
	title: CampaignTitle::create( 'Plant a Million Trees' ),
	is_active: true,
	is_open: true,
	target: CampaignTarget::create(true, 1_000_000),
);

// Update and save
$updated = $new->rename( 'Reforest the Planet' );
$command_service->save_campaign( $updated );

// Delete
$command_service->delete_campaign( $updated->get_id() );
```

Attempting to save a campaign that already exists, or to update or delete a campaign that does not exist, throws a `CampaignRepositoryExceptionInterface`.

See the [`examples/Campaigns/`](./examples/Campaigns/) directory for detailed examples.

### Events

Events describe what has happened inside the core and allow other parts of the application to react, extend behavior, or perform side effects without changing the core logic.

| Event | Triggered When |
|--------|----------------|
| **CampaignCreatedEvent** | A new campaign is created |
| **CampaignUpdatedEvent** | An existing campaign is updated |
| **CampaignDeletedEvent** | A campaign is deleted |

All events are dispatched through the configured `EventBusPort`.

## License

Fundrik Core is licensed under the [MIT License](LICENSE).



