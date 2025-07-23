<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Message;

use KeilielOliveira\Exception\Config\Config;
use KeilielOliveira\Exception\Container;
use KeilielOliveira\Exception\Exceptions\MessageBuilderException;

/**
 * Constrói uma mensagem com base no template passado, dados salvos e o rastro de execução do código.
 */
class MessageBuilder {
    public function buildMessage( string $template ): string {
        $templateKeys = $this->getTemplateKeys( $template );
        $reservedKeys = Container::getContainer()->get( Config::class )
            ->getConfig( 'reserved_keys' )
        ;

        foreach ( $templateKeys[1] as $i => $key ) {
            $keyIndex = null;

            if ( $this->hasIndexInKey( $key ) ) {
                $keyIndex = $this->getKeyIndex( $key );
                $key = $this->normalizeTemplateKey( $key );
            }

            if ( in_array( $key, array_keys( $reservedKeys ) ) ) {
                $replace = new ReservedKeysReplace( $template, $key, $keyIndex );
                $template = $replace->getReplacedTemplate();

                continue;
            }

            $replace = new DataKeysReplace( $template, $key, $keyIndex );
            $template = $replace->getReplacedTemplate();
        }

        return $template;
    }

    /**
     * Recupera as marcações de chaves no template.
     *
     * @return array<int, array<string>>
     *
     * @throws MessageBuilderException
     */
    private function getTemplateKeys( string $template ): array {
        $pattern = '/\{([^\{\}\[\]\(\)]+(\[[^\{\}\[\]\(\)]+\])?)\}/';
        preg_match_all( $pattern, $template, $matches );

        if ( null == $matches ) {
            throw new MessageBuilderException(
                "Ocorreu um erro ao recuperar as chaves do template: {$template}"
            );
        }

        return $matches;
    }

    private function hasIndexInKey( string $key ): bool {
        $pattern = '/\[[^\{\}\[\]\(\)]+\]/';

        return preg_match( $pattern, $key ) ? true : false;
    }

    private function getKeyIndex( string $key ): string {
        $pattern = '/\[([^\{\}\[\]\(\)]+)\]/';
        preg_match( $pattern, $key, $matches );

        if ( null == $matches ) {
            throw new MessageBuilderException(
                "Ocorre um erro ao recuperar o índice da chave {$key}."
            );
        }

        return $matches[1];
    }

    private function normalizeTemplateKey( string $key ): string {
        $pattern = '/\[[^\{\}\[\]\(\)]+\]/';
        $newKey = preg_replace( $pattern, '', $key );

        if ( !is_string( $newKey ) ) {
            throw new MessageBuilderException(
                "Ocorreu um erro ao normalizar a chave {$key}."
            );
        }

        return $newKey;
    }
}
