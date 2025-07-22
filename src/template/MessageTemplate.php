<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Template;

use KeilielOliveira\Exception\Config\Config;
use KeilielOliveira\Exception\Data;

class MessageTemplate {
    private Config $config;
    private Data $data;

    public function __construct( Config $config, Data $data ) {
        $this->config = $config;
        $this->data = $data;
    }

    public function createMessage( string $template ): string {
        $templateKeys = $this->getTemplateKeys( $template );
        $reservedKeysControl = new ReservedKeysControl( $this->config );
        $dataKeysControl = new DataKeysControl( $this->data );

        foreach ( $templateKeys[1] as $i => $key ) {
            $index = null;
            $value = null;

            if ( $this->hasIndex( $key ) ) {
                [$index, $key] = $this->getIndexAndNormalizeKey( $key );
            }

            if ( $this->isReservedKey( $key ) ) {
                $value = $reservedKeysControl->getKeyValue( $key, $index );
            } else {
                $value = $dataKeysControl->getKeyValue( $key, $index );
            }

            $key = $templateKeys[0][$i];
            $template = $this->replaceKeyInTemplate( $template, $key, $value );
        }

        return $template;
    }

    /**
     * @return array{
     *      list<string>,
     *      list<non-empty-string>,
     *      list<string>
     * }
     */
    private function getTemplateKeys( string $template ): array {
        $pattern = '/\{([^\{\}\[\]\(\)]+(\[[^\{\}\[\]\(\)]+\])?)\}/';
        preg_match_all( $pattern, $template, $matches );

        if ( null == $matches ) {
            throw new TemplateException( 'Ocorreu um erro inesperado ao analisar o template.' );
        }

        return $matches;
    }

    private function hasIndex( string $key ): bool {
        $pattern = '/\[[^\{\}\[\]\(\)]+\]/';

        return preg_match( $pattern, $key ) ? true : false;
    }

    /**
     * @return array{
     *      0: non-empty-string,
     *      1: string
     * }
     */
    private function getIndexAndNormalizeKey( string $key ): array {
        $pattern = '/\[([^\{\}\[\]\(\)]+)\]/';
        preg_match( $pattern, $key, $matches );

        if ( null == $matches ) {
            throw new TemplateException( "Ocorreu um erro inesperado ao analisar a chave {$key}." );
        }

        $index = $matches[1];
        $key = preg_replace( $pattern, '', $key );

        if ( !is_string( $key ) ) {
            throw new TemplateException(
                sprintf(
                    'Ocorreu um erro inesperado ao preparar a chave %s',
                    var_export( $key, true )
                )
            );
        }

        return [$index, $key];
    }

    private function isReservedKey( string $key ): bool {
        $reservedKeys = array_keys( $this->config->getReservedConfig( 'reserved_keys' ) );

        return in_array( $key, $reservedKeys );
    }

    private function replaceKeyInTemplate( string $template, string $key, mixed $value ): string {
        $value = is_string( $value ) ? $value : var_export( $value, true );
        $pattern = '/' . preg_quote( $key ) . '/';

        $template = preg_replace( $pattern, $value, $template );

        if ( !is_string( $template ) ) {
            throw new TemplateException( "Não foi possível substitui a chave {$key} pelo seu valor." );
        }

        return $template;
    }
}
