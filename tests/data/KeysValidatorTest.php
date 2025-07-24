<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Core;
use KeilielOliveira\Exception\Data\KeysValidator;
use KeilielOliveira\Exception\Exceptions\DataException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class KeysValidatorTest extends TestCase {
    public function setUp(): void {
        new Core();
    }

    public function testHasNotReachedIndexLimit(): void {
        try {
            $keys = ['a', 'b', 'c', 'd'];
            new KeysValidator( $keys );

            $this->assertTrue( true );
        } catch ( DataException $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    public function testHasReachedIndexLimit(): void {
        try {
            $keys = ['a', 'b', 'c', 'd', 'e'];
            new KeysValidator( $keys );

            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( DataException $e ) {
            $trace = $e->getTrace()[0];

            $expected = 'hasReachedIndexLimit';
            $response = $trace['function'];

            $this->assertEquals( $expected, $response );
        }
    }

    public function testIsReservedKey(): void {
        try {
            $keys = ['__CLASS__'];
            new KeysValidator( $keys );

            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( DataException $e ) {
            $trace = $e->getTrace()[0];

            $expected = 'isReservedKey';
            $response = $trace['function'];

            $this->assertEquals( $expected, $response );
        }
    }
}
