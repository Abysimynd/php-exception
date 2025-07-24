<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Exceptions\MessageBuilderException;
use KeilielOliveira\Exception\Message\ReservedKeysReplace;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ReservedKeysReplaceTest extends TestCase {
    public function testHasRequiredTrace(): void {
        try {
            new ReservedKeysReplace( '{__FILE__[10]}', '__FILE__', '1000' )
                ->getReplacedTemplate()
            ;

            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( MessageBuilderException $e ) {
            $trace = $e->getTrace()[0];
            $expected = 'getRequiredTrace';
            $response = $trace['function'];

            $this->assertEquals( $expected, $response );
        }
    }

    public function testIsReplacingKeysInTemplate(): void {
        try {
            $replace = new ReservedKeysReplace( '{__FILE__[0]}', '__FILE__', '0' );
            $response = $replace->getReplacedTemplate();

            $this->assertTrue( '{__FILE__[0]}' != $response );
        } catch ( MessageBuilderException $e ) {
            $this->fail( $e->getMessage() );
        }
    }
}
