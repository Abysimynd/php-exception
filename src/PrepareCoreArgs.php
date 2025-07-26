<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

/**
 * Prepara os parâmetros utilizados pela classe DataControl para realizar suas operações.
 */
class PrepareCoreArgs {
    private null|int|string $key;

    public function __construct( null|int|string $key ) {
        $this->key = $key;
    }

    /**
     * @return array{0: string, 1: string, 2: array<mixed>}
     */
    public function getArgs(): array {
        return [
            $this->getKey(),
            $this->getInstance(),
            $this->getData(),
        ];
    }

    private function getKey(): string {
        return is_string( $this->key ) ? $this->key : strval( $this->key );
    }

    private function getInstance(): string {
        $control = Container::getContainer()->get( InstanceControl::class );

        return $control->getValidInstance();
    }

    /**
     * @return array<mixed>
     */
    private function getData(): array {
        $instance = Container::getContainer()->get( InstanceControl::class );
        $control = Container::getContainer()->get( Data::class );

        $data = $control->getData( $instance->getValidInstance() );
        $instance->clearTempInstance();

        return $data;
    }
}
