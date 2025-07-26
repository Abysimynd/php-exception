<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\DataKeysReplace;
use KeilielOliveira\Exception\ReplaceClassFinder;
use KeilielOliveira\Exception\ReservedKeysReplace;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ReplaceClassFinderTest extends TestCase {
    /**
     * @param non-empty-string $key
     * @param class-string     $expected
     */
    #[DataProvider( 'provideKeyAndExpectedClassNameToFindClass' )]
    public function testGetClassIsReturningCorrectClass( string $key, string $expected ): void {
        $returned = new ReplaceClassFinder( $key )->getClass();

        $this->assertEquals( $expected, $returned );
    }

    /**
     * @return array<non-empty-string, array{non-empty-string, class-string}>
     */
    public static function provideKeyAndExpectedClassNameToFindClass(): array {
        return [
            'chave reservada' => [
                '__FILE__',
                ReservedKeysReplace::class,
            ],
            'chave de dados salvos' => [
                'a',
                DataKeysReplace::class,
            ],
        ];
    }
}
