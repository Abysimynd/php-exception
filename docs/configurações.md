# Configurações

A biblioteca permite a configuração de determinadas funcionalidades, abaixo estão listadas essas configurações seus valores e utilidades.

## array_index_separator

### Definição:

Essa configuração permite definir as expressões usadas para separar indices nas operações de controle de dados.

***Exemplo de separadores padrões:***
```php
<?php

use KeilielOliveira\Exception\Facade;

require_once 'vendor/autoload.php';

Facade::set( 'a.b->c=>d', 'value' );
```

No exemplo acima os conjuntos de caracteres '.', '->' e '=>' são separadores de indices padrão da biblioteca, na pratica isso significa que o valor 'value' será salvo como.

```php
<?php

$array = ['a' => ['b' => ['c' => ['d' => 'value']]]];
```

### Valores:

Essa configuração aceita qualquer **string** ou **array de strings**, porém não há uma validação sobre como o separador irá agir na chave passada, portanto ao colocar algo comum como **'a'** pode resultar em resultados inesperados, sempre coloque caracteres que não irá utilizar para separar os índices, ou use os padrões.

***Exemplo de definição de configuração:***
```php
<?php

use KeilielOliveira\Exception\Facade;

require_once 'vendor/autoload.php';

Facade::config([
    'array_index_separator' => ['>', '<'] // ou somente '>'
]);
```

## max_array_index

### Definição:

Essa configuração defini o máximo de índices permitidos em uma chave, uma observação importante é que os índices começam a contar somente após o primeiro item da chave.

***Exemplo de índices:***
```php
<?php

$key = 'a->b->c->d';

```

No exemplo acima, os índices são *'b'*, *'c'* e *'d'*, o item *'a'* conta como se fosse a base de onde a busca começará, então não é um índice. Por padrão o máximo de índices permitidos é **3**.

### Valores:

Essa configuração aceita um número inteiro como seu valor, não há um mínimo ou máximo, portanto se for definida como **0**, as chaves irão poder conter somente a base da busca sem nenhum índice, um ponto importante é que não há uma validação para ver se esse valor é positivo ou negativo, sendo assim, ao colocar como negativo, resultados inesperados irão ocorrer.

***Exemplo de definição de configuração:***
```php
<?php

use KeilielOliveira\Exception\Facade;

require_once 'vendor/autoload.php';

Facade::config([
    'max_array_index' => 10
]);
```


# Configurações reservadas

São configurações privadas que não podem ser alteradas, atualmente há somente uma.

## reserved_keys

Essa configuração defini chaves reservadas que não podem ser utilizadas sendo elas `__FILE__`, `__LINE__`, `__CLASS__` e `__METHOD__`.