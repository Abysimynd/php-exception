<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Interfaces;

/**
 * Interface das classes que são responsáveis por recuperar os valores a serem
 * substituídos nos templates de mensagens.
 */
interface KeysReplaceInterface {
    public function __construct( string $key, string $index );

    public function getKeyValue(): string;
}
