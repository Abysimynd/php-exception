<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Container;
use KeilielOliveira\Exception\Core;
use KeilielOliveira\Exception\Data\DataControl;
use KeilielOliveira\Exception\Exceptions\DataException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class DataControlTest extends TestCase {
    private Core $core;
    private DataControl $control;

    public function setUp(): void {
        $core = new Core();
        $core->use( 'A' );
        $core->set( 'a.b.c', 10 );

        $this->core = $core;
        $this->control = Container::getContainer()->get( DataControl::class );
    }

    public function testIsDefiningAndReturningData(): void {
        [$key, $value] = ['key', 'value'];
        $this->control->set( $key, $value );

        $expected = $value;
        $response = $this->control->get( $key );

        $this->assertEquals( $expected, $response );
    }

    public function testIsUpdatingData(): void {
        [$key, $value] = ['a.b.c', 'value'];
        $this->control->update( $key, $value );

        $expected = $value;
        $response = $this->control->get( $key );

        $this->assertEquals( $expected, $response );
    }

    public function testIsRemovingData(): void {
        try {
            $key = 'a.b.c';
            $this->control->remove( $key );
            $this->control->get( $key );

            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( DataException $e ) {
            $trace = $e->getTrace()[1];

            $expected = 'get';
            $response = $trace['function'];

            $this->assertEquals( $expected, $response );
        }
    }

    public function testIsCleaningData(): void {
        $this->control->clear();
        $response = $this->control->get();

        $this->assertEmpty( $response );
    }

    public function testIsCleaningAllData(): void {
        $this->core->use( 'B' );
        $this->control->clear( true );
        $this->core->use( 'A' );
        $response = $this->control->get();

        $this->assertEmpty( $response );
    }
}
