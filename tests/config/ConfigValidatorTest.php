<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Config\ConfigValidator;
use KeilielOliveira\Exception\Exceptions\ConfigException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ConfigValidatorTest extends TestCase {
    public function testIsValidConfigName(): void {
        try {
            $configArray = [
                'max_array_index' => 10,
                'array_index_separator' => ['>', '<'],
            ];

            new ConfigValidator( $configArray );
            $this->assertTrue( true );
        } catch ( ConfigException $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    /**
     * @param array<string, array<array<string, array<mixed>|int>>>
     */
    #[DataProvider( 'providerInvalidConfigName' )]
    public function testIsInvalidConfigName( array $configArray ): void {
        try {
            new ConfigValidator( $configArray );
            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( ConfigException $e ) {
            $trace = $e->getTrace()[0];

            $expected = 'isValidConfigName';
            $response = $trace['function'];

            $this->assertEquals( $expected, $response );
        }
    }

    /**
     * Prove configurações com nomes inválidos para o método testIsInvalidConfigName().
     *
     * @see testIsInvalidConfigName()
     *
     * @return array<string, array<array<array<mixed>|int>>>
     */
    public static function providerInvalidConfigName(): array {
        return [
            'configuração simples' => [
                ['invalid_name' => 10],
            ],
            'configuração privada' => [
                ['reserved_keys' => []],
            ],
        ];
    }

    /**
     * @param array<string, array<array<string, array<non-empty-string>|non-empty-string>>>
     */
    #[DataProvider( 'providerValidConfigValuesType' )]
    public function testIsValidConfigValueType( array $configArray ): void {
        try {
            new ConfigValidator( $configArray );
            $this->assertTrue( true );
        } catch ( ConfigException $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    /**
     * Prove configurações com valores de tipos validos para o método testIsValidConfigValueType().
     *
     * @see testIsValidConfigValueType()
     *
     * @return array<string, array<array<string, array<non-empty-string>|non-empty-string>>>
     */
    public static function providerValidConfigValuesType(): array {
        return [
            'tipo de valor simples' => [
                ['array_index_separator' => '>'],
            ],
            'tipo de valor complexo' => [
                ['array_index_separator' => ['<', '>']],
            ],
        ];
    }

    /**
     * @param array<string, array<array<string, array<int|non-empty-string>|int>>>
     */
    #[DataProvider( 'providerInvalidConfigValuesType' )]
    public function testIsInvalidConfigValueType( array $configArray ): void {
        try {
            new ConfigValidator( $configArray );
            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( ConfigException $e ) {
            $trace = $e->getTrace()[0];

            $expected = 'isValidConfigValueType';
            $response = $trace['function'];

            $this->assertEquals( $expected, $response );
        }
    }

    /**
     * Prove configurações com valores de tipos inválidos para o método testIsInvalidConfigValueType().
     *
     * @see testIsInvalidConfigValueType()
     *
     * @return array<string, array<array<string, array<int|non-empty-string>|int>>>
     */
    public static function providerInvalidConfigValuesType(): array {
        return [
            'tipo de valor simples' => [
                ['array_index_separator' => 10],
            ],
            'tipo de valor complexo' => [
                ['array_index_separator' => ['<', 10, '>']],
            ],
        ];
    }

    /**
     * @param array<string, array<array<string, string|string[]>>>
     */
    #[DataProvider( 'providerInvalidConfigValue' )]
    public function testIsInvalidConfigValue( array $configArray ): void {
        try {
            new ConfigValidator( $configArray );
            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( ConfigException $e ) {
            $trace = $e->getTrace()[0];

            $expected = 'isValidConfigValue';
            $response = $trace['function'];

            $this->assertEquals( $expected, $response );
        }
    }

    /**
     * Prove configurações com valores inválidos para o método testIsInvalidConfigValue().
     *
     * @see testIsInvalidConfigValue()
     *
     * @return array<string, array<array<string, string|string[]>>>
     */
    public static function providerInvalidConfigValue(): array {
        return [
            'valor simples' => [
                ['array_index_separator' => '  '],
            ],
            'valor complexo' => [
                ['array_index_separator' => ['<', '  ', '>']],
            ],
        ];
    }
}
