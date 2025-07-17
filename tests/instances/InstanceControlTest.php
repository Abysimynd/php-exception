<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Instances\InstanceControl;
use KeilielOliveira\Exception\Instances\InstanceException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class InstanceControlTest extends TestCase {
    public function testIsDefiningInstance(): void {
        $control = new InstanceControl();
        $control->setInstance( 'A' );

        $reflectionClass = new ReflectionClass( $control );
        $property = $reflectionClass->getProperty( 'instance' );
        $property->setAccessible( true );

        $this->assertNotNull( $property->getValue( $control ) );
    }

    public function testIsDefiningTempInstance(): void {
        $control = new InstanceControl();
        $control->setInstance( 'B', true );

        $reflectionClass = new ReflectionClass( $control );
        $property = $reflectionClass->getProperty( 'tempInstance' );
        $property->setAccessible( true );

        $this->assertNotNull( $property->getValue( $control ) );
    }

    public function testIsReturningInstance(): void {
        $expected = 'A';
        $control = new InstanceControl();
        $control->setInstance( $expected );
        $response = $control->getInstance();

        $this->assertEquals( $expected, $response );
    }

    public function testIsReturningTempInstance(): void {
        $expected = 'A';
        $control = new InstanceControl();
        $control->setInstance( $expected, true );
        $response = $control->getInstance( true );

        $this->assertEquals( $expected, $response );
    }

    public function testIsReturningDefinedInstance(): void {
        $expected = 'A';
        $control = new InstanceControl();
        $control->setInstance( $expected );
        $response = $control->getDefinedInstance();

        $this->assertEquals( $expected, $response );

        $expected = 'B';
        $control->setInstance( $expected, true );
        $response = $control->getDefinedInstance();

        $this->assertEquals( $expected, $response );
    }

    public function testIsReturningValidInstance(): void {
        $message = 'Não há uma instancia definida.';
        $this->expectException( InstanceException::class );
        $this->expectExceptionMessage( $message );

        $control = new InstanceControl();
        $control->getValidInstance();
    }

    public function testIsValidatingInstanceSyntax(): void {
        $instance = '{A}';
        $message = "A instancia {$instance} não possui uma sintaxe valida.";

        $this->expectException( InstanceException::class );
        $this->expectExceptionMessage( $message );

        $control = new InstanceControl();
        $control->setInstance( $instance );
    }
}
