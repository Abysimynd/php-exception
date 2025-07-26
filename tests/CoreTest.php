<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Core;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class CoreTest extends TestCase {
    private Core $core;

    public function setUp(): void {
        $this->core = new Core();

        $this->core->setInstance( 'B' );
        $this->core->set( 'a.b.c', 1 );

        $this->core->setInstance( 'A' );
    }

    /**
     * O teste abaixo também se aplica aos métodos de definição e recuperação de instancias temporárias.
     */
    public function testSetInstanceAndGetInstanceIsDefiningAndReturningInstances(): void {
        try {
            $expected = 'X';

            $this->core->setInstance( $expected );
            $returned = $this->core->getInstance();

            $this->assertNotNull( $returned );
            $this->assertEquals( $expected, $returned );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    #[DataProvider( 'provideKeysAndExpectedReturnToSetAndGet' )]
    public function testSetAndGetIsDefiningAndReturningData( string $key, mixed $expected ): void {
        try {
            $this->core->set( $key, $expected );
            $returned = $this->core->get( $key );

            $this->assertEquals( $expected, $returned );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    /**
     * @return array<string, array{string, int}>
     */
    public static function provideKeysAndExpectedReturnToSetAndGet(): array {
        return [
            'chave simples' => [
                'a',
                1,
            ],
            'chave com índices' => [
                'a.b.c',
                1,
            ],
        ];
    }

    #[DataProvider( 'provideKeysAndExpectedReturnToUpdate' )]
    public function testUpdateIsUpdatingDataInKey( string $key, mixed $expected ): void {
        try {
            $this->core->setInstance( 'B' );

            $this->core->update( $key, $expected );
            $returned = $this->core->get( $key );

            $this->assertEquals( $expected, $returned );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    /**
     * @return array<string, array{string, int}>
     */
    public static function provideKeysAndExpectedReturnToUpdate(): array {
        return [
            'chave simples' => [
                'a',
                2,
            ],
            'chave com índices' => [
                'a.b.c',
                2,
            ],
        ];
    }

    #[DataProvider( 'provideKeysAndExpectedReturnToRemove' )]
    public function testRemoveIsRemovingValueAndKeyInData( string $key, array $expected ): void {
        try {
            $this->core->setInstance( 'B' );

            $this->core->remove( $key );
            $returned = $this->core->get();

            $this->assertEquals( $expected, $returned );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    /**
     * @return array<string, array{string, array<mixed>}>
     */
    public static function provideKeysAndExpectedReturnToRemove(): array {
        return [
            'chave simples' => [
                'a',
                [],
            ],
            'chave com índices' => [
                'a.b.c',
                ['a' => ['b' => []]],
            ],
        ];
    }

    public function testClearInstanceDataIsCleaningData(): void {
        try {
            $this->core->set( 'x.y.z', 1 );
            $this->core->clearInstanceData();
            $returnedA = $this->core->get();

            $this->core->setInstance( 'B' );
            $returnedB = $this->core->get();

            $this->assertEmpty( $returnedA );
            $this->assertNotEmpty( $returnedB );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    public function testClearAllDataIsCleaningAllData(): void {
        try {
            $this->core->set( 'x.y.z', 1 );
            $this->core->clearAllData();
            $returnedA = $this->core->get();

            $this->core->setInstance( 'B' );
            $returnedB = $this->core->get();

            $this->assertEmpty( $returnedA );
            $this->assertEmpty( $returnedB );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    public function testBuildMessageIsBuildingMessage(): void {
        try {
            $pattern = '/\{[\{\}\[\]\(\)]+\}/';

            $this->core->set( 'x.y.z', true );
            $returnedA = $this->core->buildMessage( '{x.y.z}, {[B]a.b.c}, {__FILE__}' );
            $returnedB = $this->core->buildMessage( '{x.y.z}, {[B]a.b.c}' );

            // Verifica se todas as marcações do template foram substituídas.
            $this->assertFalse( preg_match( $pattern, $returnedA ) ? true : false );
            $this->assertEquals( 'true, 1', $returnedB );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }
}
