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
     * @param array<string> $path
     * @param array<mixed>  $expected
     */
    #[DataProvider( 'providerPathsAndValuesToCreateArray' )]
    public function testIsCreatingArrayWithPath( array $path, mixed $value, array $expected ): void {
        $response = ArrayHelper::createArrayWithPath( $path, $value );

        $this->assertEquals( $expected, $response );
    }

    /**
     * Prove caminhos e valores para o método testIsCreatingArrayWithPath().
     *
     * @see testIsCreatingArrayWithPath()
     *
     * @return array<string, array<array<mixed>|string>>
     */
    public static function providerPathsAndValuesToCreateArray(): array {
        return [
            'caminho e valor simples' => [
                ['a'],
                'value',
                ['a' => 'value'],
            ],
            'caminho complexo e valor simples' => [
                ['a', 'b', 'c'],
                'value',
                ['a' => ['b' => ['c' => 'value']]],
            ],
            'caminho simples e valor complexo' => [
                ['a'],
                ['b' => ['c' => 'value']],
                ['a' => ['b' => ['c' => 'value']]],
            ],
            'caminho e valor complexo' => [
                ['a', 'b', 'c'],
                [2 => 'one', 3 => 'two', 5 => 'three'],
                ['a' => ['b' => ['c' => [2 => 'one', 3 => 'two', 5 => 'three']]]],
            ],
        ];
    }

    /**
     * @param array<string> $path
     * @param array<mixed>  $array
     */
    #[DataProvider( 'providerPathsAndArraysToHasPathInArrayValidation' )]
    public function testHasPathInArrayValidation( array $path, array $array, string $assertion ): void {
        $this->{$assertion}( ArrayHelper::hasPathInArray( $path, $array ) );
    }

    /**
     * Prove caminhos e arrays para o método testHasPathInArrayValidation().
     *
     * @see testHasPathInArrayValidation()
     *
     * @return array<string, array<array<array<mixed>|string>|string>>
     */
    public static function providerPathsAndArraysToHasPathInArrayValidation(): array {
        return [
            'caminho valido' => [
                ['a', 'b'],
                ['a' => ['b' => ['c' => 'value']]],
                'assertTrue',
            ],
            'caminho invalido' => [
                ['a', 'b'],
                ['a' => ['value']],
                'assertFalse',
            ],
        ];
    }

    public function testIsUpdatingPathInArray(): void {
        $path = ['a', 'b'];
        $array = ['a' => ['b' => ['c' => 'value']]];
        $value = 'new value';

        $expected = ['a' => ['b' => 'new value']];
        $response = ArrayHelper::updatePathInArray( $path, $array, $value );

        $this->assertEquals( $expected, $response );
    }

    public function testIsRemovinggPathInArray(): void {
        $path = ['a', 'b'];
        $array = ['a' => ['b' => ['c' => 'value']]];

        $expected = ['a' => []];
        $response = ArrayHelper::removePathInArray( $path, $array );

        $this->assertEquals( $expected, $response );
    }

    public function testIsReturningArrayPathValue(): void {
        $path = ['a', 'b'];
        $array = ['a' => ['b' => ['c' => 'value']]];

        $expected = ['c' => 'value'];
        $response = ArrayHelper::getPathValueInArray( $path, $array );

        $this->assertEquals( $expected, $response );
    }
}
