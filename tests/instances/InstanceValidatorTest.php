<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Exceptions\InstanceException;
use KeilielOliveira\Exception\Instances\InstanceValidator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class InstanceValidatorTest extends TestCase {
    public function testInstanceHasValidSyntax(): void {
        try {
            $instance = '$_A*0^\/,´`&@';
            new InstanceValidator( $instance );

            $this->assertTrue( true );
        } catch ( InstanceException $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    public function testInstanceHasInvalidSyntax(): void {
        try {
            $instance = '($_A*0^\/,´`&@)';
            new InstanceValidator( $instance );

            $this->fail( 'Nenhuma instancia foi lançada.' );
        } catch ( InstanceException $e ) {
            $trace = $e->getTrace()[0];
            $expected = 'hasValidSyntax';
            $response = $trace['function'];

            $this->assertEquals( $expected, $response );
        }
    }
}
