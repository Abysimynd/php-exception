<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Message;

interface MessageReplaceInterface {
    public function __construct( string $template, string $key, ?string $index );

    public function getReplacedTemplate(): string;
}
