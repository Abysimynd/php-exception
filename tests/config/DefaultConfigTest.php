<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Config\DefaultConfig;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class DefaultConfigTest extends TestCase {
    public function testIsSettingDefaultConfig(): void {
        $defaultConfig = new DefaultConfig();
        $reflectionClass = new ReflectionClass( $defaultConfig );
        $property = $reflectionClass->getProperty( 'default' );
        $property->setAccessible( true );

        $this->assertNotEmpty( $property->getValue( $defaultConfig ) );
    }

    public function testIsReturningConfigNames(): void {
        $defaultConfig = new DefaultConfig();
        $response = $defaultConfig->getDefaultConfigNames();
        $expected = 'max_array_index';

        $this->assertTrue( in_array( $expected, $response ) );
    }

    public function testIsReturningConfigValuesTypes(): void {
        $defaultConfig = new DefaultConfig();
        $response = $defaultConfig->getDefaultConfigValuesType();
        $response = ['max_array_index' => $response['max_array_index']];
        $expected = ['max_array_index' => 'int'];

        $this->assertEquals( $expected, $response );
    }
}
