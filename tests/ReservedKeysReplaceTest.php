<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\ReservedKeysReplace;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ReservedKeysReplaceTest extends TestCase {
    public function testGetKeyValueIsReturning(): void {
        try {
            $key = '__FILE__';
            $index = '1';

            $returned = new ReservedKeysReplace( $key, $index )->getKeyValue();

            // Verifica se o nome do arquivo retornado do backtrace existe.
            $this->assertFileExists( $returned );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }
}
