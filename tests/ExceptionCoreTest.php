<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\ExceptionCodes;
use KeilielOliveira\Exception\ExceptionCore;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ExceptionCoreTest extends TestCase {
    public function setUp(): void {
        ExceptionCore::clear();
        ExceptionCore::clearCore();
        ExceptionCore::use( 'test_instance' );
    }

    public function testGetDefinedInstance(): void {
        $instance = ExceptionCore::getInstance();
        $expected = 'test_instance';
        $this->assertEquals( $expected, $instance );
    }

    public function testGetDefinedTempararyInstance(): void {
        $expected = 'test_temporary_instance';
        ExceptionCore::in( $expected );
        $instance = ExceptionCore::getTempInstance();
        $this->assertEquals( $expected, $instance );
    }

    public function testChangeInstance(): void {
        $expected = 'test_other_instance';
        ExceptionCore::use( $expected );
        $instance = ExceptionCore::getInstance();
        $this->assertEquals( $expected, $instance );
    }

    public function testAutoUnsetTemporaryInstance(): void {
        $expected = null;
        ExceptionCore::in( 'test_temporary_instance' )::set( 'test_key', 'test_value' );
        $instance = ExceptionCore::getTempInstance();
        $this->assertEquals( $expected, $instance );
    }

    public function testDefineAndGetSingleProperty(): void {
        $expected = [];
        $response = ExceptionCore::get();
        $this->assertEquals( $expected, $response );

        ExceptionCore::set( 'key', 'value' );
        $expected = ['key' => 'value'];
        $response = ExceptionCore::get();
        $this->assertEquals( $expected, $response );
    }

    public function testDefineAndGetRecursiveProperty(): void {
        ExceptionCore::set( 'key_a->key_b->key_c->7', 'value' );
        $expected = ['key_c' => [7 => 'value']];
        $response = ExceptionCore::get( 'key_a->key_b' );
        $this->assertEquals( $expected, $response );
    }

    public function testDefineAndGetSinglePropertyWithTemporaryInstance(): void {
        $expected = [];
        $response = ExceptionCore::in( 'test_temporary_instance' )::get();
        $this->assertEquals( $expected, $response );

        ExceptionCore::in( 'test_temporary_instance' )::set( 'key', 'value' );
        $expected = ['key' => 'value'];
        $response = ExceptionCore::in( 'test_temporary_instance' )::get();
        $this->assertEquals( $expected, $response );
    }

    public function testDefineAndGetRecursivePropertyWithTemporaryInstance(): void {
        ExceptionCore::in( 'test_temporary_instance' )::set( 'key_a->key_b->key_c->7', 'value' );
        $expected = ['key_c' => [7 => 'value']];
        $response = ExceptionCore::in( 'test_temporary_instance' )::get( 'key_a->key_b' );
        $this->assertEquals( $expected, $response );
    }

    public function testUpdateSingleProperty(): void {
        ExceptionCore::set( 'key', 10 );
        ExceptionCore::update( 'key', 20 );
        $expected = 20;
        $response = ExceptionCore::get( 'key' );
        $this->assertEquals( $expected, $response );
    }

    public function testUpdateRecursiveProperty(): void {
        ExceptionCore::set( 'key_a->key_b->key_c', 10 );
        ExceptionCore::update( 'key_a->key_b->key_c', 20 );
        $expected = 20;
        $response = ExceptionCore::get( 'key_a->key_b->key_c' );
        $this->assertEquals( $expected, $response );
    }

    public function testUpdateSinglePropertyWithTemporaryInstance(): void {
        ExceptionCore::in( 'test_temporary_instance' )::set( 'key', 10 );
        ExceptionCore::in( 'test_temporary_instance' )::update( 'key', 20 );
        $expected = 20;
        $response = ExceptionCore::in( 'test_temporary_instance' )::get( 'key' );
        $this->assertEquals( $expected, $response );
    }

    public function testUpdateRecursivePropertyWithTemporaryInstance(): void {
        ExceptionCore::in( 'test_temporary_instance' )::set( 'key_a->key_b->key_c', 10 );
        ExceptionCore::in( 'test_temporary_instance' )::update( 'key_a->key_b->key_c', 20 );
        $expected = 20;
        $response = ExceptionCore::in( 'test_temporary_instance' )::get( 'key_a->key_b->key_c' );
        $this->assertEquals( $expected, $response );
    }

    public function testRemoveSingleProperty(): void {
        $data = ['a' => ['b' => ['c' => [1, 2, 3]]]];
        ExceptionCore::set( 'key', $data );
        ExceptionCore::remove( 'key->a->b->c->1' );

        $expected = ['a' => ['b' => ['c' => [1, 2 => 3]]]];
        $response = ExceptionCore::get( 'key' );
        $this->assertEquals( $expected, $response );
    }

    public function testRemovePropertyWithAllChlids(): void {
        $data = ['a' => ['b' => ['c' => [1, 2, 3]]]];
        ExceptionCore::set( 'key', $data );
        ExceptionCore::remove( 'key->a' );

        $expected = [];
        $response = ExceptionCore::get( 'key' );
        $this->assertEquals( $expected, $response );
    }

    public function testRemoveSinglePropertyWithTemporaryInstance(): void {
        $data = ['a' => ['b' => ['c' => [1, 2, 3]]]];
        ExceptionCore::in( 'test_temporary_instance' )::set( 'key', $data );
        ExceptionCore::in( 'test_temporary_instance' )::remove( 'key->a->b->c->1' );

        $expected = ['a' => ['b' => ['c' => [1, 2 => 3]]]];
        $response = ExceptionCore::in( 'test_temporary_instance' )::get( 'key' );
        $this->assertEquals( $expected, $response );
    }

    public function testRemovePropertyWithAllChlidsWithTemporaryInstance(): void {
        $data = ['a' => ['b' => ['c' => [1, 2, 3]]]];
        ExceptionCore::in( 'test_temporary_instance' )::set( 'key', $data );
        ExceptionCore::in( 'test_temporary_instance' )::remove( 'key->a' );

        $expected = [];
        $response = ExceptionCore::in( 'test_temporary_instance' )::get( 'key' );
        $this->assertEquals( $expected, $response );
    }

    public function testClearSingleInstance(): void {
        ExceptionCore::use( 'A' );
        ExceptionCore::set( 'key_a', 'A' );

        ExceptionCore::use( 'B' );
        ExceptionCore::set( 'key_b', 'B' );

        ExceptionCore::clear( 'A' );

        $expected = [];
        $response = ExceptionCore::in( 'A' )::get();
        $this->assertEquals( $expected, $response );

        $expected = ['key_b' => 'B'];
        $response = ExceptionCore::in( 'B' )::get();
        $this->assertEquals( $expected, $response );
    }

    public function testClearAllInstances(): void {
        ExceptionCore::use( 'A' );
        ExceptionCore::set( 'key_a', 'A' );

        ExceptionCore::use( 'B' );
        ExceptionCore::set( 'key_b', 'B' );

        ExceptionCore::clear();

        $expected = [];
        $response = ExceptionCore::in( 'A' )::get();
        $this->assertEquals( $expected, $response );

        $expected = [];
        $response = ExceptionCore::in( 'B' )::get();
        $this->assertEquals( $expected, $response );
    }

    public function testClearCore(): void {
        ExceptionCore::use( 'A' );
        ExceptionCore::in( 'B' );
        ExceptionCore::clearCore();

        $this->expectException( Exception::class );
        $this->expectExceptionMessage( 'Não há uma instancia definida.' );
        $this->expectExceptionCode( ExceptionCodes::MISSING_INSTANCE->value );

        ExceptionCore::set( 'key', 'value' );
    }
}
