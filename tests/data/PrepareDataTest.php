<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Core;
use KeilielOliveira\Exception\Data\PrepareData;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class PrepareDataTest extends TestCase {
    private PrepareData $helper;

    public function setUp(): void {
        new Core();

        $this->helper = new PrepareData();
    }

    /**
     * @param array<string, array<array<array<array<mixed>|string>>|int|string>> $expected
     */
    #[DataProvider( 'providerArgsToPrepare' )]
    public function testIsPreparingArgs( null|int|string $key, array $expected ): void {
        $response = $this->helper->prepareArgs( [], $key, 'A' );

        $this->assertEquals( $expected, $response );
    }

    /**
     * Prove chaves para o método testIsPreparingArgs().
     *
     * @see testIsPreparingArgs()
     *
     * @return array<string, array<array<array<array<mixed>|string>>|int|string>>
     */
    public static function providerArgsToPrepare(): array {
        return [
            'chave simples' => [
                'a',
                [['a'], []],
            ],
            'chave complexa' => [
                'a.b->c=>d',
                [['a', 'b', 'c', 'd'], []],
            ],
            'chave numérica' => [
                1,
                [['1'], []],
            ],
            'chave nula' => [
                null,
                [[], []],
            ],
        ];
    }
}
