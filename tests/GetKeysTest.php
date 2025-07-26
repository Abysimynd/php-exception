<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Exceptions\InvalidKeyException;
use KeilielOliveira\Exception\GetKeys;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class GetKeysTest extends TestCase {
    /**
     * @param non-empty-string        $key
     * @param array<non-empty-string> $expected
     */
    #[DataProvider( 'provideKeysAndExpectedReturnToGetKeys' )]
    public function testGetIsReturningCorrectKeys( string $key, array $expected ): void {
        try {
            $returned = new GetKeys( $key )->get();

            $this->assertEquals( $expected, $returned );
        } catch ( Exception $e ) {
            // A InvalidKeyException pode ser lançada caso uma chave seja interpretada como invalida.
            $this->fail( $e->getMessage() );
        }
    }

    /**
     * @return array<non-empty-string, array{non-empty-string, array<non-empty-string>}>
     */
    public static function provideKeysAndExpectedReturnToGetKeys(): array {
        return [
            'chave simples' => [
                'abc',
                ['abc'],
            ],
            'chave com separador de índices' => [
                'a.b.c',
                ['a', 'b', 'c'],
            ],
            'chave com múltiplos separadores de índices' => [
                'a.b->c=>d>e',
                ['a', 'b', 'c', 'd', 'e'],
            ],
            'chave com separador de índices inútil' => [
                'a.b.c.',
                ['a', 'b', 'c'],
            ],
        ];
    }

    public function testGetKeysIsValidatingIfKeyIsReserved(): void {
        try {
            new GetKeys( '*' )->get();

            $this->fail( 'A chave não foi avaliada como reservada.' );
        } catch ( Exception $e ) {
            $this->assertInstanceOf( InvalidKeyException::class, $e );
        }
    }
}
