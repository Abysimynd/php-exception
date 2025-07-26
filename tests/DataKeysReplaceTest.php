<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Core;
use KeilielOliveira\Exception\DataKeysReplace;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class DataKeysReplaceTest extends TestCase {
    public function setUp(): void {
        $core = new Core();
        $core->setInstance( 'A' );
        $core->set( 'a', true );
        $core->setInstance( 'B' );
    }

    public function testGetKeyValueIsReturning(): void {
        try {
            $key = 'a';
            $index = 'A';
            $expected = 'true';

            $returned = new DataKeysReplace( $key, $index )->getKeyValue();

            $this->assertEquals( $expected, $returned );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }
}
