# Sobre

Esta biblioteca tem como objetivo facilitar o lançamento de exceções mais descritivas, ela abrange algumas funcionalidades simples que auxiliam nesse objetivo, observações, ela não foi pensada para usos em larga escala, sua otimização pode ser ineficiente para usos mais complexos.

---

# Documentação

A biblioteca em si possui funcionalidades bem simples, mas observação, o uso dela deve ser restrito aos metodos principais, usar outras classes internas pode e provavelmente irá acarretar em erros.

Abaixo segue-se um resumo com exemplos praticos dos possiveis usos dos metodos.

## use()

Este metodo é o principal é primeiro a ser usado, executar qualquer outro metodo antes dele, com exceção em alguns casos explicados posteriormente, irá resultar em um erro, este metodo defini uma instancia (chave) que será usada para como referencia para realizar qualquer operação após sua definição.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\ExceptionCore;

require_once 'vendor/autoload.php';

ExceptionCore::use('Erro');
/**
 * A partir desse ponto, todas as operações serão executadas na instancia (chave) 'Erro'
 * É possivel alterar a instancia a qualquer momento como exemplificado abaixo.
 * 
*/

ExceptionCore::use('Novo Erro');

/**
 * A partir desse ponto, as operações serão realizadas dentro da instancia (chave) 'Novo Erro'
*/
 
```

## in()

O metodo *in()* faz a mesma coisa que o *use()*, a unica diferença é que ele é temporario, ele defini a instancia a ser usada na execução do proximo metodo, após esse proximo metodo ser usado, a instancia volta a ser a ultima instancia definida pelo *use()*

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\ExceptionCore;

require_once 'vendor/autoload.php';

ExceptionCore::use('Erro');

ExceptionCore::in('Novo Erro');

/**
 * O proximo metodo a ser executado, será executado na instancia 'Novo Erro' e após isso a instancia definida
 * será novamente a 'Erro'
 */

```

## getInstance() e getTempInstance()

Ambos os metodos fazem a mesma coisa, eles retornam respectivamente a instance atual definida pelo *use()* e a instancia temporaria atual definida pelo *in()*, observação, para recuperar a instancia temporaria, deve-se usar o metodo *getTempInstance()* antes de executar uma ação com a instancia temporaria, após qualquer ação ser executada na instancia temporaria, sua referencia é excluida.

Outro ponto importante é que caso a instancia não tenha sido definida, **null** será retornado.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\ExceptionCore;

require_once 'vendor/autoload.php';

ExceptionCore::use('Erro');
ExceptionCore::in('Novo Erro');

ExceptionCore::getInstance(); // Ira retornar 'Erro'
ExceptionCore::getTempInstance(); // Ira retornar 'Novo Erro'

/**
 * Ao executar uma ação com a instancia temporaria, a mesma é deletada e não poderá sera recuperada
 */

// Ação com a instancia temporaria

ExceptionCore::getTempInstance(); // Ira retornar null
```

## set()

O metodo *set()* é usado para definir um valor dentro de uma instancia, observação, a instancia temporaria sempre terá prioridade caso esteja definida.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\ExceptionCore;

require_once 'vendor/autoload.php';

ExceptionCore::use('Erro');
ExceptionCore::in('Novo Erro');

ExceptionCore::set('key', 'value'); // Será armazenada dentro da instancia 'Novo Erro'
ExceptionCore::set('key', 'value'); // Será armazenada dentro da instancia 'Erro'

/**
 * O $value pode ser qualquer coisa
 * 
 * A $key pode ser passada como indices de um array
 */

// O $value será armazenado dentro da instancia[a][b][c]
ExceptionCore::set('a->b->c', 'value');
```

## update()

O metodo *update()* é usado como o proprio nome diz, para atualizar um dado, observação, se passar dois valores para a mesma chave com o *set()*, essa chave será convertida em um array com ambos os valores, a unica forma de substituir um valor é com o metodo *update()*.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\ExceptionCore;

require_once 'vendor/autoload.php';

ExceptionCore::use('Erro');
ExceptionCore::set('key', 'value'); // Será armazenada dentro da instancia 'Erro'
ExceptionCore::update('key', 'new value'); // O $value irá substituir o valor já salvo

ExceptionCore::set('key', 'new value'); // Irá ser convertido para um array[new value, new value]
ExceptionCore::update('new key', 'value'); // Irá gerar um erro pois a chave não existe
```

## remove()

Este metodo é usado caso queira remover uma determinada chave dentro dos dados passados anteriormente, observação, assim como o *update()*, irá gerar um erro caso uma chave inexistente seja passada.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\ExceptionCore;

require_once 'vendor/autoload.php';

ExceptionCore::use('Erro');
ExceptionCore::set('key', 'value'); // Será armazenada dentro da instancia 'Erro'
ExceptionCore::remove('key'); // Irá delerar a chave $key e seu respectivo valor

/**
 * Um ponto importante, caso o valor sejá um array, ele será completamente removido
 */

ExceptionCore::set('a->b->c', 'value');
ExceptionCore::remove('a->b'); // O indice b do array a será completamente removido.
```

## get()

Este metodo é usado para recuperar dados dentro da instancia, assim como os metodos anteriores, é possivel navegar nos indices livremente.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\ExceptionCore;

require_once 'vendor/autoload.php';

ExceptionCore::use('Erro');

$array = ['a' => ['b' => ['c' => 'value'], 'b_2' => 10]];
ExceptionCore::set('key', 'value'); // Será armazenada dentro da instancia 'Erro'

ExceptionCore::get('a'); // Irá retornar o valor de 'a'
ExceptionCore::get('a->b'); // Irá retornar o valor de 'b'
ExceptionCore::get('a->b_2'); // Irá retornar o valor de 'b_2'
ExceptionCore::get(); // Irá retornar todo o conteudo da instancia
```

## clear()

Este metodo é usado para limpar instancias inteiras.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\ExceptionCore;

require_once 'vendor/autoload.php';

ExceptionCore::use('Erro');

$array = ['a' => ['b' => ['c' => 'value'], 'b_2' => 10]];
ExceptionCore::set('key', 'value'); // Será armazenada dentro da instancia 'Erro'

ExceptionCore::clear('Erro'); // Agora a instancia 'Erro' não possui nenhum valor

/**
 * Outra opção é limpar todas as instancias.
 */

ExceptionCore::clear(); // Dessa forma, todas as instancias são limpas, mas não deletadas.
```

## clearCore()

Este metodo não foi pensado para usos especificos, foi adicionado para permitir testes, mas o que ele faz é redefenir as instancias.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\ExceptionCore;

require_once 'vendor/autoload.php';

ExceptionCore::use('Erro');
ExceptionCore::in('Novo Erro');
ExceptionCore::clearCore(); //Isntancias limpas agora.

ExceptionCore::getInstance(); // Irá retornar null
ExceptionCore::getTempInstance(); // Irá retornar null
```

---

# Conclusão

E esses são os principais usos da biblioteca.