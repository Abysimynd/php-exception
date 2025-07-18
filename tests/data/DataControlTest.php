<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Config\Config;
use KeilielOliveira\Exception\Data\DataControl;
use KeilielOliveira\Exception\Data\DataException;
use KeilielOliveira\Exception\Dependencies\DependenciesContainer;
use KeilielOliveira\Exception\Instances\InstanceControl;
use KeilielOliveira\Exception\Instances\InstanceException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class DataControlTest extends TestCase {
    private DependenciesContainer $container;

    public function setUp(): void {
        $this->container = new DependenciesContainer();
        $this->container->setDependecie( Config::class );
        $this->container->setDependecie( InstanceControl::class );
        $this->container->setDependecie( DataControl::class );
    }

    public function testIsValidatingIfHasInstance(): void {
        $message = 'Não há uma instancia definida.';
        $this->expectException( InstanceException::class );
        $this->expectExceptionMessage( $message );

        $control = $this->container->getDependencie( DataControl::class );
        $control->setData( 'a', 'A' );
    }

    public function testIsValidatingMaxArrayIndex(): void {
        $key = 'a->b->c->d->e';
        $message = "A chave {$key} excedeu o maximo de indices permitidos: (max: 3, keys: 4).";
        $this->expectException( DataException::class );
        $this->expectExceptionMessage( $message );

        $this->container->getDependencie( InstanceControl::class )->setInstance( 'A' );
        $control = $this->container->getDependencie( DataControl::class );
        $control->setData( $key, 'A' );
    }

    public function testIsCreatingInstance(): void {
        $instance = 'A';
        $this->container->getDependencie( InstanceControl::class )->setInstance( $instance );
        $control = $this->container->getDependencie( DataControl::class );
        $control->setData( 'a', 'A' );

        $reflectionClass = new ReflectionClass( $control );
        $property = $reflectionClass->getProperty( 'data' );
        $property->setAccessible( true );

        $this->assertTrue( isset( $property->getValue( $control )[$instance] ) );
    }

    #[DataProvider( 'providerKeyAndValueToSetData' )]
    public function testIsDefiningValue( string $key, mixed $value, array $expected ): void {
        $instance = 'A';
        $this->container->getDependencie( InstanceControl::class )->setInstance( $instance );
        $control = $this->container->getDependencie( DataControl::class );
        $control->setData( $key, $value );

        $reflectionClass = new ReflectionClass( $control );
        $property = $reflectionClass->getProperty( 'data' );
        $property->setAccessible( true );
        $response = $property->getValue( $control )[$instance];

        $this->assertEquals( $expected, $response );
    }

    /**
     * @return array<array<mixed>>
     */
    public static function providerKeyAndValueToSetData(): array {
        return [
            'chave simples' => [
                'a',
                10,
                ['a' => 10],
            ],
            'chave complexa' => [
                'a->b->c',
                10,
                ['a' => ['b' => ['c' => 10]]],
            ],
            'chave com separador =>' => [
                'a=>b=>c',
                10,
                ['a' => ['b' => ['c' => 10]]],
            ],
            'chave com separador .' => [
                'a.b.c',
                10,
                ['a' => ['b' => ['c' => 10]]],
            ],
            'chave com multiplos separadores' => [
                'a->b=>c.d',
                10,
                ['a' => ['b' => ['c' => ['d' => 10]]]],
            ],
        ];
    }

    public function testIsValidatingIfHasPathInArray(): void {
        $key = 'a.b.c';
        $instance = 'A';
        $message = sprintf(
            'Não foi possível localizar o caminho %s dentro da instance %s.',
            implode( ' -> ', explode( '.', $key ) ),
            $instance
        );

        $this->expectException( DataException::class );
        $this->expectExceptionMessage( $message );

        $this->container->getDependencie( InstanceControl::class )->setInstance( $instance );
        $control = $this->container->getDependencie( DataControl::class );
        $control->getData( $key );
    }

    public function testIsReturningPathValue(): void {
        $instance = 'A';
        $this->container->getDependencie( InstanceControl::class )->setInstance( $instance );

        $control = $this->container->getDependencie( DataControl::class );
        $control->setData( 'a.b.c', 10 );

        $expected = 10;
        $response = $control->getData( 'a->b=>c' );
        $this->assertEquals( $expected, $response );
    }

    public function testIsUpdatingPathValue(): void {
        $instance = 'A';
        $this->container->getDependencie( InstanceControl::class )->setInstance( $instance );

        $control = $this->container->getDependencie( DataControl::class );
        $control->setData( 'a.b.c', 10 );
        $control->updateData( 'a', [10] );

        $expected = [10];
        $response = $control->getData( 'a' );
        $this->assertEquals( $expected, $response );
    }

    public function testIsRemovingPathValue(): void {
        $instance = 'A';
        $this->container->getDependencie( InstanceControl::class )->setInstance( $instance );

        $control = $this->container->getDependencie( DataControl::class );
        $control->setData( 'a.b.c', 10 );
        $control->removeData( 'a' );

        $expected = [];
        $response = $control->getData();
        $this->assertEquals( $expected, $response );
    }

    public function testIsCleaningData(): void {
        // Defini dados na instancia A
        $instance = 'A';
        $this->container->getDependencie( InstanceControl::class )->setInstance( $instance );
        $control = $this->container->getDependencie( DataControl::class );
        $control->setData( 'a.b.c', 10 );

        // Defini dados na instancia B e logo em seguida limpa a instancia B
        $instance = 'B';
        $this->container->getDependencie( InstanceControl::class )->setInstance( $instance );
        $control = $this->container->getDependencie( DataControl::class );
        $control->setData( 'a.b.c', 10 );
        $control->clearData();

        // Verifica se a instancia B está limpa
        $expected = [];
        $response = $control->getData();
        $this->assertEquals( $expected, $response );

        // Verifica se a instancia A não foi afetada pela limpeza da instancia B
        $instance = 'A';
        $this->container->getDependencie( InstanceControl::class )->setInstance( $instance );
        $expected = ['a' => ['b' => ['c' => 10]]];
        $response = $control->getData();
        $this->assertEquals( $expected, $response );

        // Volta para a instancia B e limpa todas as instancias
        $instance = 'B';
        $this->container->getDependencie( InstanceControl::class )->setInstance( $instance );
        $control->clearData( true );

        // Verifica se a instancia A foi limpa
        $instance = 'A';
        $this->container->getDependencie( InstanceControl::class )->setInstance( $instance );
        $expected = [];
        $response = $control->getData();
        $this->assertEquals( $expected, $response );
    }
}
