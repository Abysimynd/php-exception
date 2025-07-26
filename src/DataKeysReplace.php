<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

use KeilielOliveira\Exception\Interfaces\KeysReplaceInterface;

/**
 * Recupera o valor das marcações referentes aos dados salvos nos templates de mensagens.
 */
class DataKeysReplace implements KeysReplaceInterface {
    private string $key;
    private string $index;

    public function __construct( string $key, string $index ) {
        $this->key = $key;
        $this->index = $index;
    }

    public function getKeyValue(): string {
        $this->setInstance();
        [$key, $instance, $data] = new PrepareCoreArgs( $this->key )->getArgs();
        $key = '*' == $key ? null : $key;

        $control = new DataControl( $data );
        $preparedData = $control->get( $key );

        return is_string( $preparedData ) ? $preparedData : var_export( $preparedData, true );
    }

    private function setInstance(): void {
        if ( null != $this->index ) {
            $control = Container::getContainer()->get( InstanceControl::class );
            $control->setInstance( $this->index, true );
        }
    }
}
