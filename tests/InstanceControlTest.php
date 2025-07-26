<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Exceptions\InstanceException;
use KeilielOliveira\Exception\InstanceControl;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class InstanceControlTest extends TestCase {
    public function testSetInstanceIsValidatingInstance(): void {
        try {
            $control = new InstanceControl();
            $instance = '{A}'; // Instancia com sintaxe invalida.

            $control->setInstance( $instance );

            $this->fail( 'A instancia não foi avaliada como invalida.' );
        } catch ( Exception $e ) {
            $this->assertInstanceOf( InstanceException::class, $e );
        }
    }
}
