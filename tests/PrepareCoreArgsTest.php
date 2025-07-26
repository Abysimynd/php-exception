<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Core;
use KeilielOliveira\Exception\PrepareCoreArgs;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class PrepareCoreArgsTest extends TestCase {
    public function setUp(): void {
        $core = new Core();
        $core->setInstance( 'A' );
        $core->set( 'a.b.c', 1 );
    }

    public function testGetArgsIsReturningCorrectArgs(): void {
        try {
            $expected = ['10', 'A', ['a' => ['b' => ['c' => 1]]]];

            $prepareArgs = new PrepareCoreArgs( 10 );
            $returned = $prepareArgs->getArgs();

            $this->assertEquals( $expected, $returned );
        } catch ( Exception $e ) {
            // A InstanceException pode ser lançada já que uma instancia valida deve ser retornada.
            $this->fail( $e->getMessage() );
        }
    }
}
