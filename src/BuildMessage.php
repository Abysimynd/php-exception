<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

use KeilielOliveira\Exception\Exceptions\MessageException;
use KeilielOliveira\Exception\Interfaces\KeysReplaceInterface;

/**
 * Cria uma mensagem como base em templates, dados salvos e backtrace.
 */
class BuildMessage {
    private string $template;

    public function __construct( string $template ) {
        $this->template = $template;
    }

    public function getMessage(): string {
        $this->buildMessage();

        return $this->template;
    }

    private function buildMessage(): void {
        $keysMarkers = $this->getTemplateKeysMarkers();

        foreach ( $keysMarkers as $key => $keyMarker ) {
            $key = $this->getKey( $keyMarker );
            $index = $this->getKeyIndex( $keyMarker );

            $replaceClass = $this->getReplaceClass( $key );

            $replaceObject = new $replaceClass( $key, $index );
            $value = $replaceObject->getKeyValue();

            $pattern = '/\{' . preg_quote( $keyMarker ) . '\}/';
            $message = preg_replace( $pattern, $value, $this->template );

            if ( null === $message ) {
                throw new MessageException( 'Ocorreu um erro ao gerar a mensagem.' );
            }

            $this->template = $message;
        }
    }

    /**
     * @return array<string>
     *
     * @throws MessageException
     */
    private function getTemplateKeysMarkers(): array {
        $pattern = '/\{((\[[^\{\}\[\]\(\)]+\])?[^\{\}\[\]\(\)]+(\[[^\{\}\[\]\(\)]+\])?)\}/';
        preg_match_all( $pattern, $this->template, $matches );

        if ( null == $matches ) {
            throw new MessageException(
                sprintf( 'Não foi possível recuperar as chaves do template %s', $this->template )
            );
        }

        return $matches[1];
    }

    private function getKey( string $keyMarker ): string {
        $pattern = '/(\[[^\{\}\[\]\(\)]+\])?([^\{\}\[\]\(\)]+)(\[[^\{\}\[\]\(\)]+\])?/';
        preg_match( $pattern, $keyMarker, $matches );

        return !empty( $matches[2] ) ? $matches[2] : strval( null );
    }

    private function getKeyIndex( string $keyMarker ): string {
        $pattern = '/\[([^\{\}\[\]\(\)]+)\]/';
        preg_match( $pattern, $keyMarker, $matches );

        return !empty( $matches[1] ) ? $matches[1] : strval( null );
    }

    /**
     * Identifica e retorna o nome da classe que será responsável por recuperar o valor da chave.
     *
     * @return class-string<KeysReplaceInterface>
     */
    private function getReplaceClass( string $key ): string {
        $finder = new ReplaceClassFinder( $key );

        return $finder->getClass();
    }
}
