<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Config\Config;
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

        $this->assertNotEmpty( $property->getValue( $config ) );
    }

    public function testIsDefiningCustomConfig(): void {
        $config = new Config();
        $config->setConfig( ['max_array_index' => 10] );

        $reflectionClass = new ReflectionClass( $config );
        $property = $reflectionClass->getProperty( 'config' );
        $property->setAccessible( true );

        $expected = 10;
        $response = $property->getValue( $config )['max_array_index'];
        $this->assertEquals( $expected, $response );
    }

    public function testIsReturningConfigValue(): void {
        $config = new Config();

        $expected = 3;
        $response = $config->getConfig( 'max_array_index' );
        $this->assertEquals( $expected, $response );
    }
}
