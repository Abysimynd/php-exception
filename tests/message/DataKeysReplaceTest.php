<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Facade;
use KeilielOliveira\Exception\Message\DataKeysReplace;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class DataKeysReplaceTest extends TestCase {
    public function testIsReplacingTemplateKey(): void {
        Facade::use( 'A' );
        Facade::set( 'a', 'A' );
        Facade::use( 'B' );
        Facade::set( 'b', 'B' );

        $expected = 'B A';
        $template = '{b} {a[A]}';
        $args = [['b', null], ['a', 'A']];

        foreach ( $args as $key => $arg ) {
            $template = new DataKeysReplace( $template, ...$arg )->getReplacedTemplate();
        }

        $this->assertEquals( $expected, $template );
    }
}
