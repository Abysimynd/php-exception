# Controle de dados

O controle de dados é a parte mais importante dessa biblioteca, ele permite as realizar as operações básicas de controle de dados *adicionar*, *atualizar*, *remover* e *recuperar* nos dados das instancias.

# Métodos

Aqui estão listados todos os métodos de controle de dados e suas descrições.

## set()

Este método possibilita a inserção de dados em uma instancias.

### parâmetros:

- *string* **$key**: chave onde os dados serão salvos na instancia.
- *mixed* **$value**: o valor que será salvo.


### retorno:

ESte método retorna *void*.

### exceções:

- **InstanceException**: quando nenhuma instancia foi definida anteriormente.
- **InvalidKeyException**: quando o número de indices de uma chave excede o máximo.
- **InvalidKeyException**: quando uma chave reservada é usada.

### observações:

As chaves utilizadas neste método possuem uma sintaxe própria ao qual é explicada em **[sintaxe das chaves](./controle_de_dados.md#chaves)**. 

Outra peculiaridade é em como esse método salva os dados, ele pega a chave passada e cria um array com a chave e o valor, após isso ele mescla os dados existentes com esse novo array gerado usando o **array_merge_recursive()**, a peculiaridade que isso causa é que, caso duas chaves iguais sejam usadas, o valor antigo não vai ser sobreposto, e sim sera mesclado com o valor atual em um novo array contendo ambos os valores.

### exemplos de uso:
```php
<?php

use KeilielOliveira\Exception\CoreFacade;

require_once 'vendor/autoload.php';

CoreFacade::setInstance( 'instance_A' );
CoreFacade::set('key_a', 'value');

// Sintaxe completa das chaves.
CoreFacade::set('key_a.key_b.key_c', 'value');
```

## update()

Este método atualiza o valor de uma chave por outro.

### parâmetros:

- *string* **$key**: chave que deve ser atualizada.
- *mixed* **$value**: novo valor a ser usado.

### retorno

Este método retorna *void*.

### exceções:

- **InstanceException**: quando nenhuma instancia foi definida anteriormente.
- **InvalidKeyException**: quando o número de indices de uma chave excede o máximo.
- **InvalidKeyException**: quando uma chave reservada é usada.
- **DataPathException**: quando a chave não é encontrada nos dados.

### observações:

As chaves utilizadas neste método possuem uma sintaxe própria ao qual é explicada em **[sintaxe das chaves](./controle_de_dados.md#chaves)**.

### exemplos de uso:
```php
<?php

use KeilielOliveira\Exception\CoreFacade;

require_once 'vendor/autoload.php';

CoreFacade::setInstance( 'instance_A' );
CoreFacade::set('key_a.key_b.key_c', 'value');
CoreFacade::update('key_a.key_b.key_c', 'new value');
```

## remove()

Este método remove determinada chave dos dados de uma instancias.

### parâmetros:

- *string* **$key**: a chave a ser removida.

### retorno:

Este método retorna *void*.

### exceções:

- **InstanceException**: quando nenhuma instancia foi definida anteriormente.
- **InvalidKeyException**: quando o número de indices de uma chave excede o máximo.
- **InvalidKeyException**: quando uma chave reservada é usada.
- **DataPathException**: quando a chave não é encontrada nos dados.

### observações:

As chaves utilizadas neste método possuem uma sintaxe própria ao qual é explicada em **[sintaxe das chaves](./controle_de_dados.md#chaves)**. 

Outro ponto importante é que este método não remove apenas o valor da chave, ele remove a própria chave junto.

### exemplos de uso:
```php
<?php

use KeilielOliveira\Exception\CoreFacade;

require_once 'vendor/autoload.php';

CoreFacade::setInstance( 'instance_A' );
CoreFacade::set('key_a.key_b.key_c', 'value');
CoreFacade::remove('key_a');
```

## get()

Este método realiza uma busca por determinada chave e retorna seu respectivo valor se houver.

### parâmetros:

- *string*|*null* **$key**: a chave a ser buscada.

### retorno:

Este método retorna *mixed*.

### exceções:

- **InstanceException**: quando nenhuma instancia foi definida anteriormente.
- **InvalidKeyException**: quando o número de indices de uma chave excede o máximo.
- **InvalidKeyException**: quando uma chave reservada é usada.
- **DataPathException**: quando a chave não é encontrada nos dados.

### observações:

As chaves utilizadas neste método possuem uma sintaxe própria ao qual é explicada em **[sintaxe das chaves](./controle_de_dados.md#chaves)**. 

Outro ponto importante é que caso a chave seja passada como *null*, valor padrão da mesma, ele irá retornar todos os dados salvos na instancia.

### exemplos de uso:
```php
<?php

use KeilielOliveira\Exception\CoreFacade;

require_once 'vendor/autoload.php';

CoreFacade::setInstance( 'instance_A' );
CoreFacade::set('key_a.key_b.key_c', 'value');
CoreFacade::get('key_a');
```

## clearInstanceData()

Este método limpa todos os dados salvos da instancia definida atualmente.

### parâmetros:

Não há parâmetros.

### retorno:

Este método retorna *void*.

### exceções:

- **InstanceException**: quando nenhuma instancia foi definida anteriormente.

### observações:

Este método não só limpa os valores da instancia, ele remove a própria instancia, mas isso não impacta diretamente no uso da instancia futuramente, já que a mesma sempre é definida antes de uma operação ser realizada caso a mesma já não tenha sido definida.

### exemplos de uso:
```php
<?php

use KeilielOliveira\Exception\CoreFacade;

require_once 'vendor/autoload.php';

CoreFacade::setInstance( 'instance_A' );
CoreFacade::set('key_a.key_b.key_c', 'value');
CoreFacade::clearInstanceData();
```

## clearAllData()

Este método limpa todos os dados de todas as instancias.

### parâmetros:

Não há parâmetros.

### retorno:

Este método retorna *void*.

### exceções:

Nenhuma exceção é lançada.

### observações:

Este método não só limpa os valores da instancia, ele remove a própria instancia, mas isso não impacta diretamente no uso da instancia futuramente, já que a mesma sempre é definida antes de uma operação ser realizada caso a mesma já não tenha sido definida.


### exemplos de uso:
```php
<?php

use KeilielOliveira\Exception\CoreFacade;

require_once 'vendor/autoload.php';

CoreFacade::setInstance( 'instance_A' );
CoreFacade::set('key_a.key_b.key_c', 'value');
CoreFacade::clearAllData();
```

# Chaves

As chaves usadas nos métodos de controle de dados possuem uma sintaxe própria de índices que permite uma navegação simplificada.

## Sintaxe

A sintaxe delas pode ser qualquer conjunto de caracteres que não contenham *{}*, *[]* ou *()*, mas outra característica são os separadores de índices, atualmente existem os seguintes separadores *.*, *->*, *=>* e *>* , estes separadores quebram a chave em diversos pedaços permitindo uma navegação interna pelo conteúdo de uma instancia.

Cada separador pode ser usado individualmente ou misturado, como preferir.

### exemplos de uso:
```php
<?php

use KeilielOliveira\Exception\CoreFacade;

require_once 'vendor/autoload.php';

CoreFacade::setInstance( 'instance_A' );
CoreFacade::set('key_a.key_b->key_c=>key_c>key_d', 'value');
// Isso irá gerar o array [key_a => [key_b => [key_c => [key_d => value]]]]
```

## Limite de índices

Outro ponto a se ater, e que há um limitador de índices, esse limitador por padrão é de uma chave base e cinco índices, mais que isso e uma exceção **InvalidKeyException** será lançada.

### exemplos de uso:
```php
<?php

use KeilielOliveira\Exception\CoreFacade;

require_once 'vendor/autoload.php';

CoreFacade::setInstance( 'instance_A' );

// (chave base = key_a) + (3 índices = key_b, key_c, key_d) = chave valida
CoreFacade::set('key_a.key_b->key_c=>key_c>key_d', 'value');

// (chave base = a) + (6 índices = b, c, d, e, f, g) = chave invalida
CoreFacade::set('a.b.c.d.e.f.g', 'value');
```

## Chaves reservadas

Existem um conjunto de chaves que são de uso exclusivo do sistema, esse conjunto é *__FUNCTION__*, *__LINE__*, *__FILE__*, *__CLASS__*, *__OBJECT__*, *__TYPE__*, *__ARGS__* e *\**, os primeiros sete são reservados para a navegação no *backtrace* do código e o ultimo é um coringa usado para retornar todos os valores de uma instancia na geração de mensagens.