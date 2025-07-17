<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Helpers\ArrayHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ArrayHelperTest extends TestCase {
    /**
     * @param array<int|string> $path
     * @param array<mixed>      $expected
     */
    #[DataProvider( 'providerPathsAndValuesToCreateArray' )]
    public function testIsCreatingArray( array $path, mixed $value, array $expected ): void {
        $response = ArrayHelper::createArrayWithPath( $path, $value );
        $this->assertEquals( $expected, $response );
    }

    /**
     * @return array<array<mixed>>>
     */
    public static function providerPathsAndValuesToCreateArray(): array {
        return [
            'array simples' => [
                ['a'],
                10,
                ['a' => 10],
            ],
            'array complexo' => [
                ['a', 'b', 'c'],
                10,
                ['a' => ['b' => ['c' => 10]]],
            ],
            'array simples com valor complexo' => [
                ['a'],
                ['b' => ['c' => 10]],
                ['a' => ['b' => ['c' => 10]]],
            ],
        ];
    }

    /**
     * @param array<int|string> $path
     * @param array<mixed>      $array
     */
    #[DataProvider( 'providerPathsAndArraysToValidate' )]
    public function testHasPathInArray( array $path, array $array, bool $expected ): void {
        $response = ArrayHelper::hasPathInArray( $path, $array );
        $this->assertEquals( $expected, $response );
    }

    /**
     * @return array<array<mixed>>
     */
    public static function providerPathsAndArraysToValidate(): array {
        return [
            'caminho simples valido' => [
                ['a'],
                ['a' => ['b' => ['c' => 10]]],
                true,
            ],
            'caminho simples invalido' => [
                ['b'],
                ['a' => ['b' => ['c' => 10]]],
                false,
            ],
            'caminho complexo valido' => [
                ['a', 'b', 'c'],
                ['a' => ['b' => ['c' => 10]]],
                true,
            ],
            'caminho complexo invalido' => [
                ['a', 'b', 'd'],
                ['a' => ['b' => ['c' => 10]]],
                false,
            ],
        ];
    }

    /**
     * @param array<int|string> $path
     * @param array<mixed>      $array
     * @param array<mixed>      $expected
     */
    #[DataProvider( 'providerPathsAndArraysAndValuesToUpdate' )]
    public function testIsUpdatingPathInArray( array $path, array $array, mixed $value, array $expected ): void {
        $response = ArrayHelper::updatePathInArray( $path, $array, $value );
        $this->assertEquals( $expected, $response );
    }

    /**
     * @return array<array<mixed>>
     */
    public static function providerPathsAndArraysAndValuesToUpdate(): array {
        return [
            'atualização de caminho simples' => [
                ['a'],
                ['a' => ['b' => ['c' => 10]]],
                10,
                ['a' => 10],
            ],
            'atualização de caminho complexa' => [
                ['a', 'b', 'c'],
                ['a' => ['b' => ['c' => 10]]],
                20,
                ['a' => ['b' => ['c' => 20]]],
            ],
        ];
    }

    /**
     * @param array<int|string> $path
     * @param array<mixed>      $array
     * @param array<mixed>      $expected
     */
    #[DataProvider( 'providerPathsAndArraysToRemovePath' )]
    public function testIsRemovingPathInArray( array $path, array $array, array $expected ): void {
        $response = ArrayHelper::removePathInArray( $path, $array );
        $this->assertEquals( $expected, $response );
    }

    /**
     * @return array<array<mixed>>
     */
    public static function providerPathsAndArraysToRemovePath(): array {
        return [
            'removendo caminho simples' => [
                ['a'],
                ['a' => ['b' => ['c' => 10]]],
                [],
            ],
            'removendo caminho complexo' => [
                ['a', 'b', 'c'],
                ['a' => ['b' => ['c' => 10]]],
                ['a' => ['b' => []]],
            ],
        ];
    }
}
