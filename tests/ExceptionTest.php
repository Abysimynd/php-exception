<?php

use KeilielOliveira\Exception\Exception;
use KeilielOliveira\Exception\ExceptionCodes;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ExceptionTest extends TestCase {
    public function setUp(): void {
        Exception::clear();
    }

    public function testThrowException(): void {
        $message = 'Exceção de teste!';
        $code = 100;
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( $message );
        $this->expectExceptionCode( $code );

        throw new Exception( $message, $code );
    }

    public function testIsSettingProperty(): void {
        $key = 'key';
        $value = 'value';

        Exception::set( $key, $value );
        $this->assertEquals( $value, Exception::get( $key ) );
    }

    public function testIsSettingPropertyWithMagicMethod(): void {
        $key = 'key';
        $value = 'value';
        $e = new Exception();

        $e->{$key} = $value; // @phpstan-ignore property.notFound
        $this->assertEquals( $value, $e->{$key} );
    }

    public function testIsValidatingKeyExists(): void {
        $key = 'key';
        $value = 'value';
        Exception::set( $key, $value );

        $message = "A propriedade '{$key}' já foi definida.";
        $code = ExceptionCodes::PROPERTY_ALREADY_DEFINED->value;
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( $message );
        $this->expectExceptionCode( $code );

        Exception::set( $key, $value );
    }

    public function testIsValidatingKeyNotExistsInMethodGet(): void {
        $key = 'key';

        $message = "Propriedade '{$key}' não encontrada";
        $code = ExceptionCodes::PROPERTY_NOT_FOUND->value;
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( $message );
        $this->expectExceptionCode( $code );

        Exception::get( $key );
    }

    public function testIsValidatingKeyNotExistsInMethodRemove(): void {
        $key = 'key';

        $message = "Propriedade '{$key}' não encontrada";
        $code = ExceptionCodes::PROPERTY_NOT_FOUND->value;
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( $message );
        $this->expectExceptionCode( $code );

        Exception::remove( $key );
    }

    public function testIsRemovingData(): void {
        $key = 'key';
        Exception::set( $key, 'value' );
        Exception::remove( $key );

        $message = "Propriedade '{$key}' não encontrada";
        $code = ExceptionCodes::PROPERTY_NOT_FOUND->value;
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( $message );
        $this->expectExceptionCode( $code );

        Exception::get( $key );
    }

    public function testIsCleaningData(): void {
        $key = 'key';
        Exception::set( $key, 'value' );
        Exception::clear();

        $message = "Propriedade '{$key}' não encontrada";
        $code = ExceptionCodes::PROPERTY_NOT_FOUND->value;
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( $message );
        $this->expectExceptionCode( $code );

        Exception::get( $key );
    }
}
