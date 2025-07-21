<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Config\Config;
use KeilielOliveira\Exception\Data\DataControl;
use KeilielOliveira\Exception\Instances\InstanceControl;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class DataControlTest extends TestCase {
    private Config $config;
    private InstanceControl $instanceControl;
    private DataControl $dataControl;

    public function setUp(): void {
        $this->config = new Config();
        $this->instanceControl = new InstanceControl();
        $this->dataControl = new DataControl( $this->config, $this->instanceControl );

        $this->instanceControl->setInstance( 'B' );
        $this->instanceControl->setInstance( 'A' );
    }

    /**
     * @param array<mixed> $expected
     */
    #[DataProvider( 'providerDataToSetAndGet' )]
    public function testIsDefiningAndReturningData( int|string $key, mixed $value, array $expected ): void {
        $this->dataControl->setData( $key, $value );
        $response = $this->dataControl->getData();

        $this->assertEquals( $expected, $response );
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function providerDataToSetAndGet(): array {
        return [
            'dado simples' => [
                'a',
                'value',
                ['a' => 'value'],
            ],
            'dado complexo' => [
                'a->b=>c.d',
                [1, 2, 3],
                ['a' => ['b' => ['c' => ['d' => [1, 2, 3]]]]],
            ],
        ];
    }

    /**
     * @param array<mixed> $expected
     */
    #[DataProvider( 'providerDataToUpdate' )]
    public function testIsUpdatingData( int|string $key, mixed $value, array $expected ): void {
        $this->dataControl->setData( 'a.b.c', 'value' );
        $this->dataControl->updateData( $key, $value );
        $response = $this->dataControl->getData();

        $this->assertEquals( $expected, $response );
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function providerDataToUpdate(): array {
        return [
            'dado simples' => [
                'a',
                10,
                ['a' => 10],
            ],
            'dado complexo' => [
                'a->b=>c',
                10,
                ['a' => ['b' => ['c' => 10]]],
            ],
        ];
    }

    /**
     * @param array<mixed> $expected
     */
    #[DataProvider( 'providerDataToRemove' )]
    public function testIsRemovingData( int|string $key, array $expected ): void {
        $this->dataControl->setData( 'a.b.c', 'value' );
        $this->dataControl->removeData( $key );
        $response = $this->dataControl->getData();

        $this->assertEquals( $expected, $response );
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function providerDataToRemove(): array {
        return [
            'chave simples' => [
                'a',
                [],
            ],
            'chave complexa' => [
                'a=>b.c',
                ['a' => ['b' => []]],
            ],
        ];
    }

    public function testIsCleaningData(): void {
        // Defini valores nas instancias A e B.
        $this->dataControl->setData( 'key', 'value' );
        $this->instanceControl->setInstance( 'B', true );
        $this->dataControl->setData( 'key', 'value' );
        $this->instanceControl->clearTempInstance();

        // Limpa a instancia atual A e verifica se a limpeza ocorreu.
        $this->dataControl->clearData();
        $expected = [];
        $response = $this->dataControl->getData();
        $this->assertEquals( $expected, $response );

        // Muda para a instancia B e verifica se ela foi afetada.
        $this->instanceControl->setInstance( 'B', true );
        $expected = ['key' => 'value'];
        $response = $this->dataControl->getData();
        $this->assertEquals( $expected, $response );

        // Retorna para a instancia A e limpa todas as instancias após verifica se a instancia B foi limpa.
        $this->instanceControl->clearTempInstance();
        $this->dataControl->clearData( true );
        $this->instanceControl->setInstance( 'B' );
        $expected = [];
        $response = $this->dataControl->getData();
        $this->assertEquals( $expected, $response );
    }
}
