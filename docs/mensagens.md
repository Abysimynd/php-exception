# Criação de mensagens

Outra funcionalidade é a criação de mensagens através de templates.

***Exemplo de uso:***
```php
<?php

use KeilielOliveira\Exception\Facade;

require_once 'vendor/autoload.php';

Facade::use('A');
Facade::set('id', 10);
Facade::use('B');
Facade::set('err', 'erro');

echo Facade::createMessage('{err} code {id[A]} file {__FILE__}');
```

## createMessage()

Esse método cria uma mensagem com base no template recebido, dados salvos e traços de execução de código.

### parâmetros:

- string **$template**: O template da mensagem a ser gerada.

# Explicação

No exemplo anterior a algumas coisas a serem notadas, a primeira é que são definidas duas instancias **A** e **B** na instancia **A** é salvo o *id* e na instancia **B** o *err*.

As chaves podem ser passadas dentro de **{chave}** no template, as mesmas serão substituídas pelos seus respectivos valores, outro ponto importante é que, a sintaxe das chaves aqui é a mesma usada no registro, caso não tenha visto a sintaxe das chaves, veja [Operações](./operações.md#chaves).

Outro ponto perceptível é que as chaves são buscadas na instancia definida atualmente, caso não saiba o que são instancias veja [Instancias](./instancias.md), porém há uma forma de burlar isso, a sintaxe das chaves no template tem uma pequena alteração, a possibilidade do **\[name\]** no final da chave, isso permite definir em qual instancia a busca deve ser realizada e uma coisa a mais que será explicada posteriormente, mas no caso do exemplo, permite buscar o *id* dentro da instancia **A**.

Por fim, a ultima chave, ela é uma chave reservada, as chaves reservadas servem para buscar indices no *backtrace* de execução do código, no caso do exemplo, buscar o nome do arquivo, a outra coisa ao qual os **\[\]** no final permitem, é especificar em qual índice do *backtrace* deverá ser buscado valor, por padrão ele procura no índice 0.

## observações:

Primeiramente, caso algum índice **\[\]** ou chave não seja encontrado, uma exceção será lançada. Por fim, caso o valor da marcação seja de um tipo que não seja uma *string*, ele será convertido para uma *string* usando a função nativa do php **var_export()**.