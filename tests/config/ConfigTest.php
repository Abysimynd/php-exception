<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Config\Config;
use KeilielOliveira\Exception\Exceptions\ConfigException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ConfigTest extends TestCase {
    public function testIsDefiningAndReturningConfig(): void {
        try {
            $expected = 10;

            $configArray = [
                'max_array_index' => $expected,
            ];

            $config = new Config();
            $config->setConfig( $configArray );
            $response = $config->getConfig( 'max_array_index' );

            $this->assertEquals( $expected, $response );
        } catch ( ConfigException $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    public function testIsDefiningReservedConfig(): void {
        try {
            $config = new Config();
            $response = $config->getConfig( 'reserved_keys' );

            $this->assertNotEmpty( $response );
        } catch ( ConfigException $e ) {
            $this->fail( $e->getMessage() );
        }
    }
}
