# Uso

Essa biblioteca foi criada sem um proposito especifico, ela age como um repositório de dados para contextualizar exceções.

***Uso simples:***
```php
<?php

use KeilielOliveira\Exception\Facade;

require_once 'vendor/autoload.php';

try {
    Facade::use( 'Instance_A' );
    Facade::set( 'key', 'value' );

    throw new Exception( Facade::createMessage( '{key}' ) );
} catch ( Exception $e ) {
    
}
```

Este é um exemplo de uso simples da biblioteca, para compreender melhor seu uso e funcionamento leia .