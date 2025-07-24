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
    public function testIsReturningDefaultConfigNames(): void {
        $default = new DefaultConfig();

        $expected = 'max_array_index';
        $response = $default->getDefaultConfigNames();

        $this->assertTrue( in_array( $expected, $response ) );
    }

    public function testIsReturningDefaultConfigValuesTypes(): void {
        $default = new DefaultConfig();

        $expected = ['max_array_index' => 'int', 'array_index_separator' => ['string']];
        $response = $default->getDefaultConfigValuesType();

        $this->assertEquals( $expected, $response );
    }
}
