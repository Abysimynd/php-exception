<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Data;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class MessageTemplateTest extends TestCase {
    private Data $data;

    public function setUp(): void {
        $this->data = new Data();
        $this->data->use( 'A' );
        $this->data->set( 'a.b.c.d', 10 );
        $this->data->use( 'B' );
        $this->data->set( 'a', [10, 20, 30] );
    }

    #[DataProvider( 'providerTemplatesToCreator' )]
    public function testIsCreatingMessage( string $template, string $expected ): void {
        $response = $this->data->createMessage( $template );
        $this->assertEquals( $expected, $response );
    }

    /**
     * @return array<array<mixed>>
     */
    public static function providerTemplatesToCreator(): array {
        return [
            [
                '{a}',
                var_export( [10, 20, 30], true ),
            ],
            [
                '{a.1}',
                '20',
            ],
            [
                '{a[A]}',
                var_export( ['b' => ['c' => ['d' => 10]]], true ),
            ],
            [
                '{a->b->c->d[A]}',
                '10',
            ],
            [
                '{__FILE__} {__CLASS__}',
                __FILE__ . ' ' . Data::class,
            ],
        ];
    }
}
