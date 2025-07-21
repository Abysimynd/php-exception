<?php

declare(strict_types = 1);
use KeilielOliveira\Exception\Config\ConfigException;
use KeilielOliveira\Exception\Config\ConfigValidator;
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
            $config = ['max_array_index' => 10];
            new ConfigValidator( $config );

            $this->assertTrue( true );
        } catch ( ConfigException|Exception $e ) {
            $this->fail( 'Uma exceção foi lançada quando não deveria.' );
        }
    }

    public function testIsValidConfigNameWithInvalidConfig(): void {
        try {
            $config = ['!key' => '!value'];
            new ConfigValidator( $config );

            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( ConfigException $e ) {
            $trace = $e->getTrace()[0];
            $expected = ['class' => ConfigValidator::class, 'method' => 'isValidConfigName'];
            $response = ['class' => $trace['class'], 'method' => $trace['function']];

            $this->assertInstanceOf( ConfigException::class, $e );
            $this->assertEquals( $expected, $response );
        }
    }

    /**
     * @param array<string, array<mixed>>
     */
    #[DataProvider( 'providerValidConfigToValidateConfigValueType' )]
    public function testIsValidConfigValueType( array $config ): void {
        try {
            new ConfigValidator( $config );
            $this->assertTrue( true );
        } catch ( ConfigException|Exception $e ) {
            $this->fail( 'Uma exceção foi lançada quando não deveria.' );
        }
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function providerValidConfigToValidateConfigValueType(): array {
        return [
            'tipo simples' => [
                ['max_array_index' => 10],
            ],
            'tipo complexo' => [
                ['array_index_separator' => ['a', 'b', 'c']],
            ],
        ];
    }

    /**
     * @param array<string, array<mixed>>
     */
    #[DataProvider( 'providerInValidConfigToValidateConfigValueType' )]
    public function testIsValidConfigValueTypeWithInvalidConfig( array $config ): void {
        try {
            new ConfigValidator( $config );
            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( ConfigException|Exception $e ) {
            $trace = $e->getTrace()[0];
            $expected = ['class' => ConfigValidator::class, 'method' => 'isValidConfigValueType'];
            $response = ['class' => $trace['class'], 'method' => $trace['function']];

            $this->assertInstanceOf( ConfigException::class, $e );
            $this->assertEquals( $expected, $response );
        }
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function providerInvalidConfigToValidateConfigValueType(): array {
        return [
            'tipo simples' => [
                ['max_array_index' => '10'],
            ],
            'tipo complexo' => [
                ['array_index_separator' => ['a', 'b', 10]],
            ],
        ];
    }

    /**
     * @param array<string, array<mixed>>
     */
    #[DataProvider( 'providerValidConfigToValidateConfigValue' )]
    public function testIsValidConfigValue( array $config ): void {
        try {
            new ConfigValidator( $config );
            $this->assertTrue( true );
        } catch ( ConfigException|Exception $e ) {
            $this->fail( 'Uma exceção foi lançada quando não deveria.' );
        }
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function providerValidConfigToValidateConfigValue(): array {
        return [
            'valor simples' => [
                ['array_index_separator' => '-'],
            ],
            'valor complexo' => [
                ['array_index_separator' => ['-', '_', '=']],
            ],
        ];
    }

    /**
     * @param array<string, array<mixed>>
     */
    #[DataProvider( 'providerInvalidConfigToValidateConfigValue' )]
    public function testIsValidConfigValueWithInvalidConfig( array $config ): void {
        try {
            new ConfigValidator( $config );
            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( ConfigException|Exception $e ) {
            $trace = $e->getTrace()[0];
            $expected = ['class' => ConfigValidator::class, 'method' => 'isValidConfigValue'];
            $response = ['class' => $trace['class'], 'method' => $trace['function']];

            $this->assertInstanceOf( ConfigException::class, $e );
            $this->assertEquals( $expected, $response );
        }
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function providerInvalidConfigToValidateConfigValue(): array {
        return [
            'valor simples' => [
                ['array_index_separator' => ''],
            ],
            'valor complexo' => [
                ['array_index_separator' => []],
            ],
            'array de valores simples' => [
                ['array_index_separator' => ['a', '', 'c']],
            ],
        ];
    }
}
