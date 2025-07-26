<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Validators\DataPathValidator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class DataPathValidatorTest extends TestCase {
    public function testDataPathValidatorIsValidatingIfPathExist(): void {
        try {
            $path = ['a', 'b', 'c'];
            $array = ['a' => ['b' => ['c' => 1]]];

            $validator = new DataPathValidator( $path, $array );

            $this->assertTrue( $validator->validate() );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    public function testDataPathValidatorIsValidatingIfPathNotExist(): void {
        try {
            $path = ['a', 'b', 'c', 'd'];
            $array = ['a' => ['b' => ['c' => 1]]];

            $validator = new DataPathValidator( $path, $array, false );

            $this->assertTrue( $validator->validate() );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }
}
