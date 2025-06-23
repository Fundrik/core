<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests\Support;

use Fundrik\Core\Support\TypeCaster;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass( TypeCaster::class )]
final class TypeCasterTest extends TestCase {

	#[Test]
	public function it_casts_to_bool_correctly(): void {

		$this->assertTrue( TypeCaster::to_bool( true ) );
		$this->assertFalse( TypeCaster::to_bool( false ) );
		$this->assertTrue( TypeCaster::to_bool( 1 ) );
		$this->assertFalse( TypeCaster::to_bool( 0 ) );
		$this->assertTrue( TypeCaster::to_bool( 'true' ) );
		$this->assertFalse( TypeCaster::to_bool( 'false' ) );
		$this->assertTrue( TypeCaster::to_bool( '1' ) );
		$this->assertFalse( TypeCaster::to_bool( '0' ) );
		$this->assertFalse( TypeCaster::to_bool( 'no' ) );
		$this->assertTrue( TypeCaster::to_bool( 'yes' ) );
		$this->assertFalse( TypeCaster::to_bool( null ) );
	}

	#[Test]
	public function it_casts_to_int_correctly(): void {

		$this->assertSame( 123, TypeCaster::to_int( '123' ) );
		$this->assertSame( 0, TypeCaster::to_int( 'abc' ) );
		$this->assertSame( 5, TypeCaster::to_int( 5.99 ) );
		$this->assertSame( 0, TypeCaster::to_int( null ) );
		$this->assertSame( 1, TypeCaster::to_int( true ) );
		$this->assertSame( 0, TypeCaster::to_int( false ) );
	}

	#[Test]
	public function it_casts_to_string_correctly(): void {

		$this->assertSame( '123', TypeCaster::to_string( 123 ) );
		$this->assertSame( '1', TypeCaster::to_string( true ) );
		$this->assertSame( '', TypeCaster::to_string( null ) );
		$this->assertSame( '5.7', TypeCaster::to_string( 5.7 ) );
		$this->assertSame( 'text', TypeCaster::to_string( 'text' ) );
	}

	#[Test]
	public function it_casts_to_id_as_int_when_numeric(): void {

		$this->assertSame( 123, TypeCaster::to_id( '123' ) );
		$this->assertSame( 0, TypeCaster::to_id( 0 ) );
		$this->assertSame( 456, TypeCaster::to_id( 456 ) );
	}

	#[Test]
	public function it_casts_to_id_as_string_when_not_numeric(): void {

		$this->assertSame( 'abc-123-def', TypeCaster::to_id( 'abc-123-def' ) );
		$this->assertSame( 'uuid-like-id', TypeCaster::to_id( 'uuid-like-id' ) );
		$this->assertSame( '00123', TypeCaster::to_id( '00123' ) ); // not valid int, so string preserved.
		$this->assertSame( 'abc123', TypeCaster::to_id( 'abc123' ) );
	}
}
