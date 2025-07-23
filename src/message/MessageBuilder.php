<?php

declare(strict_types=1);

namespace KeilielOliveira\Exception\Message;

use KeilielOliveira\Exception\Config\Config;
use KeilielOliveira\Exception\Data;
use KeilielOliveira\Exception\Exceptions\MessageBuilderException;

class MessageBuilder {

    // Dependências
    private Config $config;
    private Data $data;

    /**
     * @param \KeilielOliveira\Exception\Config\Config $config
     * @param \KeilielOliveira\Exception\Data $data
     */
    public function __construct(Config $config, Data $data) {
        $this->config = $config;
        $this->data = $data;
    }

    public function buildMessage(string $template): string {
        $templateKeys = $this->getTemplateKeys($template);
        $reservedKeys = $this->config->getReservedConfig('reserved_keys');
        $message = $template;

        foreach ($templateKeys[1] as $i => $key) {

            $keyIndex = null;

            if($this->hasIndexInKey($key)) {
                $keyIndex = $this->getKeyIndex($key);
                $key = $this->normalizeTemplateKey($key);
            }

            if(in_array($key, $reservedKeys)) {
                $replace = new ReservedKeysReplace($message, $key, $keyIndex);
                $message = $replace->getReplacedTemplate();
            }else {

            }
        }

        return $message;
    }

    private function getTemplateKeys(string $template): array {
        $pattern = '/\{([^\{\}\[\]\(\)]+(\[[^\{\}\[\]\(\)]+\])?)\}/';
        preg_match_all($pattern, $template, $matches);

        if(null == $matches) {
            throw new MessageBuilderException(
                "Ocorreu um erro ao recuperar as chaves do template: $template"
            );
        }

        return $matches;
    }

    private function hasIndexInKey(string $key): bool {
        $pattern = '/\[[^\{\}\[\]\(\)]+\]/';
        return preg_match($pattern, $key) ? true : false;
    }

    private function getKeyIndex(string $key): string {
        $pattern = '/\[([^\{\}\[\]\(\)]+)\]/';
        preg_match($pattern, $key, $matches);

        if(null == $matches) {
            throw new MessageBuilderException(
                "Ocorre um erro ao recuperar o índice da chave $key."
            );
        }

        return $matches[1];
    }

    private function normalizeTemplateKey(string $key): string {
        $pattern = '/\[[^\{\}\[\]\(\)]+\]/';
        $newKey = preg_replace($pattern, '', $key);

        if(!is_string($newKey)) {
            throw new MessageBuilderException(
                "Ocorreu um erro ao normalizar a chave $key."
            );
        }

        return $newKey;
    }
}