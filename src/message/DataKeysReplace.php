<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Message;

use KeilielOliveira\Exception\Container;
use KeilielOliveira\Exception\Data\DataControl;
use KeilielOliveira\Exception\Exceptions\MessageBuilderException;
use KeilielOliveira\Exception\Instances\InstanceControl;

/**
 * Substitui as marcações de dados salvos no template.
 */
class DataKeysReplace implements MessageReplaceInterface {
    private string $template;
    private string $key;
    private ?string $index;

    public function __construct( string $template, string $key, ?string $index ) {
        $this->template = $template;
        $this->key = $key;
        $this->index = $index;
    }

    public function getReplacedTemplate(): string {
        $valueToReplace = $this->getReplaceValue();
        $pattern = $this->getReplacePattern();
        $template = preg_replace( $pattern, $valueToReplace, $this->template );

        if ( !is_string( $template ) ) {
            throw new MessageBuilderException(
                "Ocorreu um erro ao substituir a chave {$pattern} pelo seu valor."
            );
        }

        return $template;
    }

    private function getReplaceValue(): string {
        $this->setDataInstance();

        return $this->getData();
    }

    private function setDataInstance(): void {
        if ( null != $this->index ) {
            Container::getContainer()->get( InstanceControl::class )
                ->set( $this->index, true )
            ;
        }
    }

    private function getData(): string {
        $data = Container::getContainer()->get( DataControl::class )
            ->get( $this->key )
        ;

        return is_string( $data ) ? $data : var_export( $data, true );
    }

    private function getReplacePattern(): string {
        if ( null != $this->index ) {
            return sprintf( '/\{%s\[%s\]\}/', $this->key, $this->index );
        }

        return sprintf( '/\{%s\}/', $this->key );
    }
}
