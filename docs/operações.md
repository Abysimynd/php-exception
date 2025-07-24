# Controle de dados

Os métodos de controle de dados são responsáveis, como o próprio nome diz, por controlar os dados dentro das instancias, eles oferecem todas as funcionalidades básicas para o controle de dados como descrito abaixo.

## set()

Este método é responsável por inserir dados em uma instancia.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\Facade;

require_once 'vendor/autoload.php';

Facade::use('A');
Facade::set('key', 'value');
```

### parâmetros:

- string **$key**: chave onde o valor será salvo, no final deste arquivo há algumas especificações relevantes sobre as mesmas.
- mixed **$value**: O valor a ser salvo, pode ser qualquer tipo de valor sem exceções.

### obervações:

Algo que pode ocorrer é a inserção de dois valores em uma mesma chave, ou mesmo a tentativa de sobrescrever um valor dessa forma, porém este método não permite isso, ao passar dois valores para uma mesma chave, eles são mesclados em um novo array contendo ambos os valores.

## update()

Este método permite a atualização do valor de determinada chave anteriormente inserida, caso a chave não seja encontrada, uma exceção será lançada.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\Facade;

require_once 'vendor/autoload.php';

Facade::use('A');
Facade::set('key', 'value');

Facade::update('key', 'new value');
```

### parâmetros:

- string **$key**: A chave cujo valor será atualizado, no final deste arquivo há algumas especificações relevantes sobre as mesmas.
- mixed **$value**: Novo valor da chave, podendo ser de qualquer tipo sem exceções.

### remove()

Este método permite a remoção de determinada chave e seu respectivo valor, caso a chave não seja encontrada, uma exceção será lançada.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\Facade;

require_once 'vendor/autoload.php';

Facade::use('A');
Facade::set('key', 'value');

Facade::remove('key');
```

### parâmetros:

- string **$key**: A chave que será removida juntamente com seu respectivo valor, no final deste arquivo há algumas especificações relevantes sobre as mesmas.

### observações:

A chave também sera removida, ficando assim indisponível para o acesso posterior.

## get()

Este método permite a recuperação do valor de determinada chave, caso a chave não seja encontrada, uma exceção será lançada.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\Facade;

require_once 'vendor/autoload.php';

Facade::use('A');
Facade::set('key', 'value');

Facade::get('key');
```

### parâmetros:

- string **$key**: A chave a ser buscada, no final deste arquivo há algumas especificações relevantes sobre as mesmas.

### observações:

Por padrão a chave é **null**, e ao passa-la como **null**, todos os dados da instancia serão retornados.

## clearInstance()

Este método limpa todos os dados de uma instancia.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\Facade;

require_once 'vendor/autoload.php';

Facade::use('A');
Facade::set('key', 'value');

Facade::clearInstance();
```

## clearData()

Este método limpa todos os dados registrados de todas as instancias.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\Facade;

require_once 'vendor/autoload.php';

Facade::use('A');
Facade::set('key', 'value');

Facade::clearData();
```

# Chaves

As chaves são utilizadas em quase todas as operações é possuem uma sintaxe simples mas também útil, normalmente seria possível acessar somente um valor dentro da instancia, e caso o mesmo seja um array, teria de se navegar nele após recuperar o mesmo, mas as chaves possuem uma sintaxe que possibilita navegar livremente dessa forma.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\Facade;

require_once 'vendor/autoload.php';

Facade::use('A');
Facade::set('a.b.c.d', 'value');

Facade::update('a.b.c', 'new value');
```

No método **set()**, caso não exista esse caminho, um array contendo este caminho e o valor passado no último índice será criado e salvo. No método **update()**, o índice *c* dentro do array *b* que por sua vez está dentro do array *a*, será atualizado, qualquer método que permita o uso de chaves suporta essa sintaxe.

Essa sintaxe é possível graças aos separadores de índices representado nos exemplos pelo caractere **'.'**, por padrão existem os separadores **'.'**, **'->'** e **'=>'**, eles podem ser mesclados no uso também, também é possível alterar esses separadores através das configurações, para isso veja.