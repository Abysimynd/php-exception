<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\PrepareData;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class PrepareDataTest extends TestCase {
    /**
     * @param array<non-empty-string> $path
     * @param array<mixed>            $expected
     */
    #[DataProvider( 'providePathAndExpectedValueToCreateArray' )]
    public function testCreateArrayWithPathIsCreatingArray( array $path, array $expected ): void {
        $builder = new PrepareData();
        $returned = $builder->createArrayWithPath( $path, 1 );

        $this->assertEquals( $expected, $returned );
    }

    /**
     * @return array<non-empty-string, array<array<mixed>|int|non-empty-string>>
     */
    public static function providePathAndExpectedValueToCreateArray(): array {
        return [
            'caminho simples' => [
                ['a'],
                ['a' => 1],
            ],
            'caminho complexo' => [
                ['a', 'b', 'c'],
                ['a' => ['b' => ['c' => 1]]],
            ],
        ];
    }

    /**
     * @param array<non-empty-string> $path
     * @param array<mixed>            $array
     * @param array<mixed>            $expected
     */
    #[DataProvider( 'providePathAndArrayAndExpectedValueToUpdateArray' )]
    public function testUpdatePathInArrayIsUpdatingArray( array $path, array $array, array $expected ): void {
        $builder = new PrepareData();
        $returned = $builder->updatePathInArray( $path, $array, 1 );

        $this->assertEquals( $expected, $returned );
    }

    /**
     * @return array<non-empty-string, array<array<mixed>|int|non-empty-string>>
     */
    public static function providePathAndArrayAndExpectedValueToUpdateArray(): array {
        return [
            'caminho simples' => [
                ['a'],
                ['a' => ['b' => ['c' => 2]]],
                ['a' => 1],
            ],
            'caminho complexo' => [
                ['a', 'b', 'c'],
                ['a' => ['b' => ['c' => 2]]],
                ['a' => ['b' => ['c' => 1]]],
            ],
        ];
    }

    /**
     * @param array<non-empty-string> $path
     * @param array<mixed>            $array
     * @param array<mixed>            $expected
     */
    #[DataProvider( 'providePathAndArrayAndExpectedValueToRemoveArrayPathAndValue' )]
    public function testRemovePathInArrayIsRemovingArrayPathAndValue( array $path, array $array, array $expected ): void {
        $builder = new PrepareData();
        $returned = $builder->removePathInArray( $path, $array );

        $this->assertEquals( $expected, $returned );
    }

    /**
     * @return array<non-empty-string, array<array<mixed>|int|non-empty-string>>
     */
    public static function providePathAndArrayAndExpectedValueToRemoveArrayPathAndValue(): array {
        return [
            'caminho simples' => [
                ['a'],
                ['a' => ['b' => ['c' => 2]]],
                [],
            ],
            'caminho complexo' => [
                ['a', 'b', 'c'],
                ['a' => ['b' => ['c' => 2]]],
                ['a' => ['b' => []]],
            ],
        ];
    }

    /**
     * @param array<non-empty-string> $path
     * @param array<mixed>            $array
     */
    #[DataProvider( 'providePathAndArrayAndExpectedValueToGetPathValue' )]
    public function testGetPathValueInArrayIsReturningPathValue( array $path, array $array, mixed $expected ): void {
        $builder = new PrepareData();
        $returned = $builder->getPathValueInArray( $path, $array );

        $this->assertEquals( $expected, $returned );
    }

    /**
     * @return array<non-empty-string, array<array<mixed>|int|non-empty-string>>
     */
    public static function providePathAndArrayAndExpectedValueToGetPathValue(): array {
        return [
            'caminho simples' => [
                ['a'],
                ['a' => ['b' => ['c' => 2]]],
                ['b' => ['c' => 2]],
            ],
            'caminho complexo' => [
                ['a', 'b', 'c'],
                ['a' => ['b' => ['c' => 2]]],
                2,
            ],
        ];
    }
}
