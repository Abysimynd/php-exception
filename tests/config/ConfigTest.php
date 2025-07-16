<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Config\Config;
use KeilielOliveira\Exception\Config\InvalidConfigException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ConfigTest extends TestCase {
    public function testIsDefiningDefaultConfig(): void {
        $config = new Config();
        $reflectionClass = new ReflectionClass( $config );
        $property = $reflectionClass->getProperty( 'config' );
        $property->setAccessible( true );
        $this->assertNotNull( $property->getValue( $config ) );
        $this->assertNotEmpty( $property->getValue( $config ) );
    }

    public function testIsReturningConfig(): void {
        $name = 'max_array_index';
        $config = new Config();
        $expected = 3;
        $response = $config->getConfig( $name );
        $this->assertEquals( $expected, $response );
    }

    public function testIsDefiningCustomConfig(): void {
        $name = 'max_array_index';
        $expected = 10;
        $config = new Config();
        $config->setConfig( [$name => $expected] );
        $response = $config->getConfig( $name );
        $this->assertEquals( $expected, $response );
    }

    public function testIsValidatingIfConfigExists(): void {
        $name = 'invalid_config';
        $message = "A configuração '{$name}' não existe.";
        
        $this->expectException( InvalidConfigException::class );
        $this->expectExceptionMessage( $message );

        $config = new Config();
        $config->setConfig( [$name => 10] );
    }

    public function testIsValidatingIfTypeIsValid(): void {
        $name = 'max_array_index';
        $message = "A configuração '{$name}' espera receber (int).";

        $this->expectException( InvalidConfigException::class );
        $this->expectExceptionMessage( $message );

        $config = new Config();
        $config->setConfig( [$name => 'string'] );
    }
}
