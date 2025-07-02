<?php

use KeilielOliveira\Exception\Config;
use KeilielOliveira\Exception\Exception;
use KeilielOliveira\Exception\ExceptionCodes;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class MessageTemplateTest extends TestCase {
    public function setUp(): void {
        Exception::clear();
    }

    public function testIsDefiningTemplateData(): void {
        $key = 'key';
        $value = 'value';
        Exception::set( $key, $value, true );

        $templateKey = Config::TEMPLATE_DATA_KEY->value;
        $data = Exception::get( $templateKey );
        $this->assertEquals( [$key => $value], $data );
    }

    public function testIsValidatingTemplateDataExists(): void {
        $message = 'Não foi possível recuperar os dados usados na geração da mensagem.';
        $code = ExceptionCodes::TEMPLATE_DATA_NOT_DEFINED->value;

        $this->expectException( Exception::class );
        $this->expectExceptionMessage( $message );
        $this->expectExceptionCode( $code );

        Exception::createMessage( '' );
    }

    public function testIsValidatingParamsUsedInMessage(): void {
        $key = 'key';
        $value = ['value'];
        Exception::set( $key, $value, true );

        $message = "Valores usados para gerar uma mensagem devem ser dos tipos (string, int, float), array foi recebido no indice '{$key}'.";
        $code = ExceptionCodes::INVALID_REPLACEMENT_VALUE->value;

        $this->expectException( Exception::class );
        $this->expectExceptionMessage( $message );
        $this->expectExceptionCode( $code );

        Exception::createMessage( '@key' );
    }

    public function testIsValidatingMessage(): void {
        $key = 'key';
        $value = 'value';
        $otherKey = 'otherKey';
        Exception::set( $key, $value, true );

        $message = "Dado '{$otherKey}' para geração da mensagem faltando.";
        $code = ExceptionCodes::MISSING_TEMPLATE_DATA->value;

        $this->expectException( Exception::class );
        $this->expectExceptionMessage( $message );
        $this->expectExceptionCode( $code );

        Exception::createMessage( "@key @{$otherKey}" );
    }
}
