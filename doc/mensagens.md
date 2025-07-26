# Geração de mensagens

Uma das funcionalidades mais úteis dessa biblioteca, é a geração de mensagens a partir de templates, ela permite uma geração simples de mensagem a partir de um template, dados salvos e *backtrace*.

# Métodos

Aqui esta listado o método de geração de mensagens e sua descrição.

## buildMessage()

Este método gera uma mensagens a partir de um template com marcações, dados salvos e *backtrace*.

### parâmetros:

- *string* **$template**: o template usado para gerar a mensagem.

### retorno:

este método retorna uma string contendo a mensagem gerada.

### exceções:

- **MessageException**: quando erros inesperados ocorrem.
- **InstanceException**: quando não há uma instancia definida.
- **DataPathException**: quando uma chave não é encontrada.
- **MessageException**: quando um índices ou chave não é encontrado(a) no *backtrace*.

### observações:

Este método possui uma sintaxe única que será explicada em **[sintaxe dos templates de mensagens](./mensagens.md#sintaxe-do-template)**.

### exemplos de uso:
```php
<?php

use KeilielOliveira\Exception\CoreFacade;

require_once 'vendor/autoload.php';

CoreFacade::setInstance( 'instance_A' );
CoreFacade::set('key_a.key_b.key_c', 'value');
CoreFacade::setInstance( 'instance_B' );
CoreFacade::set('key_x.key_y.key_z', 'value');

echo CoreFacade::buildMessage( '{key_x.key_y.key_z}, {[instance_A]*}, {__FILE__}' );
```

# Sintaxe do template

O template segue uma sintaxe própria com marcações que indicam o que deve ser substituído é pelo que, essas marcações são definidas entre *{}*, dentro das *{}* pode ser passado uma chave da instancia atual *{key_a}*, uma chave de uma instancia especifica *{\[instance_A\]key_a}*, o *\[instance\]* pode ser definido tanto antes quanto após a chave, também é possível usar a chave reservada coringa *{\*}* que é usada para indicar que todo o conteúdo da instancia deve ser colocado ali.

Essas chaves seguem a mesma sintaxe das usadas nos métodos de operações de controle de dados encontrada aqui **[sintaxe das chaves](./controle_de_dados.md#chaves)**.

Também é possível usar as chaves reservadas para o *backtrace*, cada parâmetro possível do *backtrace* é acessível através da sintaxe *{__PARÂMETRO_DO_BACKTRACE_TODO_EM_MAIÚSCULO__}*, também é possível usar os índices entre *\[\]* para navegar no *backtrace*, *{__FILE__\[3\]}*, assim como nas instancias, ele pode ser definido tanto antes quanto após a chave reservada. 

Por fim, um ponto importante é que, caso o valor da chave não seja uma *string*, ele será automaticamente convertido em uma através do uso da função *var_export()* do php.