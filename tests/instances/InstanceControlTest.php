<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Exceptions\InstanceException;
use KeilielOliveira\Exception\Instances\InstanceControl;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class InstanceControlTest extends TestCase {
    #[DataProvider( 'providerInstancesToSetAndGet' )]
    public function testIsDefiningAndReturningInstances( string $instance, bool $isTemp ): void {
        try {
            $control = new InstanceControl();
            $control->set( $instance, $isTemp );
            $response = $control->get( $isTemp );

            $this->assertNotNull( $response );
        } catch ( InstanceException $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    /**
     * Prove instancias para o método testIsDefiningAndReturningInstances().
     *
     * @see testIsDefiningAndReturningInstances()
     *
     * @return array<string, array<bool|string>>
     */
    public static function providerInstancesToSetAndGet(): array {
        return [
            'instancia permanente' => [
                'A',
                false,
            ],
            'instancia temporária' => [
                'B',
                true,
            ],
        ];
    }

    /**
     * @param array<string, bool> $instancesToSet
     */
    #[DataProvider( 'providerInstancesToSetAndGetDefined' )]
    public function testIsReturningDefinedInstance( array $instancesToSet, ?string $expected ): void {
        $control = new InstanceControl();

        foreach ( $instancesToSet as $instance => $isTemp ) {
            $control->set( $instance, $isTemp );
        }
        $response = $control->getDefined();

        $this->assertEquals( $expected, $response );
    }

    /**
     * Prove instancias para o método testIsReturningDefinedInstance().
     *
     * @see testIsReturningDefinedInstance()
     *
     * @return array<string, array<null|array<string, bool>|string>>
     */
    public static function providerInstancesToSetAndGetDefined(): array {
        return [
            'instancia permanente' => [
                ['A' => false],
                'A',
            ],
            'instancia temporária' => [
                ['B' => true, 'A' => false],
                'B',
            ],
            'nenhuma instancia' => [
                [],
                null,
            ],
        ];
    }

    public function testIsReturningValidInstance(): void {
        try {
            $control = new InstanceControl();
            $control->getValid();

            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( InstanceException $e ) {
            $trace = $e->getTrace()[0];
            $expected = 'getValid';
            $response = $trace['function'];

            $this->assertEquals( $expected, $response );
        }
    }
}
