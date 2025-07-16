<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Config;

use Symfony\Component\Console\Exception\InvalidOptionException;

class Config {
    private array $config;

    public function __construct() {
        $default = new DefaultConfig();
        $this->config = $default->getDefaultConfig();
    }

    public function setConfig( array $config ): void {
        new ConfigValidator( $config );
        $this->config = array_merge( $this->config, $config );
    }

    public function getConfig( string $config ): mixed {
        if ( !isset( $this->config[$config] ) ) {
            throw new InvalidOptionException(
                "A configuração '{$config}' não existe."
            );
        }

        return $this->config[$config];
    }
}
