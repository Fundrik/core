<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests;

use Fundrik\Core\App;
use Fundrik\Core\Infrastructure\Interfaces\DependencyProviderInterface;
use Fundrik\Core\Infrastructure\Internal\Container;
use Fundrik\Core\Infrastructure\Internal\ContainerManager;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use stdClass;

#[CoversClass( App::class )]
#[UsesClass( ContainerManager::class )]
final class AppTest extends FundrikTestCase {

	private App $app;

	protected function setUp(): void {

		parent::setUp();

		$this->app = new App();
	}

	#[Test]
	public function container_returns_same_instance(): void {

		$container1 = $this->app->container();
		$container2 = $this->app->container();

		$this->assertInstanceOf( Container::class, $container1 );
		$this->assertEquals( $container1, $container2 );
	}

	#[Test]
	public function register_bindings_registers_singletons(): void {

		$container = $this->app->container();

		$provider = Mockery::mock( DependencyProviderInterface::class );

		$bindings = [
			'abstract1' => fn() => (object) [ 'tag' => 'stdClass1' ],
			'group'     => [
				'abstract2a' => fn() => (object) [ 'tag' => 'stdClass2a' ],
				'abstract2b' => fn() => (object) [ 'tag' => 'stdClass2b' ],
			],
		];

		$provider
			->shouldReceive( 'get_bindings' )
			->once()
			->with( '' )
			->andReturn( $bindings );

		$this->assertFalse( $container->has( 'abstract1' ) );
		$this->assertFalse( $container->has( 'abstract2a' ) );
		$this->assertFalse( $container->has( 'abstract2b' ) );

		$this->app->register_bindings( $provider );

		$this->assertSame( 'stdClass1', $container->get( 'abstract1' )->tag );
		$this->assertSame( 'stdClass2a', $container->get( 'abstract2a' )->tag );
		$this->assertSame( 'stdClass2b', $container->get( 'abstract2b' )->tag );
	}

	#[Test]
	public function register_bindings_passes_category_to_provider(): void {

		$container = $this->app->container();

		$provider = Mockery::mock( DependencyProviderInterface::class );

		$category = 'platform';

		$bindings = [
			'some.abstract' => fn() => new stdClass(),
		];

		$provider
			->shouldReceive( 'get_bindings' )
			->once()
			->with( $this->identicalTo( $category ) )
			->andReturn( $bindings );

		$this->assertFalse( $container->has( 'some.abstract' ) );

		$this->app->register_bindings( $provider, $category );

		$this->assertInstanceOf( stdClass::class, $container->get( 'some.abstract' ) );
	}
}
