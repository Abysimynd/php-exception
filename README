# Sobre

---

Esta biblioteca tem como finalidade auxiliar no lançamento de exceções permitindo a passagem de mais contexto para a exceção e auxiliando na geração de mensagens. Ela utiliza como base a classe Exception do php.

# Documentação

Seu uso é extremamente simples assim como a mesma, ela não é otimizada nem pensada para usos em larga escala ou complexos.

***Uso simples:***
```php
<?php

use KeilielOliveira\Exception\Exception;

require_once 'vendor/autoload.php';

try {
    
    throw new Exception("Ocorreu um erro!");

} catch (Exception $e) {
    $e->getMessage();
}
```

Ela pode ser usada como uma classe de exceções padrão, mas sua principal funcionalidade não é essa, e sim a possibilidade de adicionar maior contexto.

***Adicionando contexto:***
```php
<?php

use KeilielOliveira\Exception\Exception;

require_once 'vendor/autoload.php';

try {
    
    Exception::set('message', 'Ocorreu um erro!');

    throw new Exception(Exception::get('message'));

} catch (Exception $e) {
    $e->getMessage();
}
```

Dessa forma é possível passar quantos parametros quiser e recupera-los posteriormente para criar um contexto para a exceção sendo lançada, mas isso tem uma limitação, o metodo *get()* não conseguem buscar valores que não estejam na primeira camada, ou seja, ele não consegue ascessar um indice de um array definido pelo *set()*, ele só consegue acessar as chaves passadas diretamente no parametro *$key* do *set()*.

Os metodos *set()* e *get()* também possuem outra forma de serem chamados, através dos metodos magicos *__set()* e *__get()*.

***Metodos magicos:***
```php
<?php

use KeilielOliveira\Exception\Exception;

require_once 'vendor/autoload.php';

try {
    
    $e = new Exception();
    $e->message = 'Ocorreu um erro!';

    throw new Exception($e->message);

} catch (Exception $e) {
    echo $e->getMessage();
}
```

É possivel usar eles definindo propriedades dinamicamente com os metodos magicos, eles não definem propriedades de classe dinamicamente, eles somente chamam os respectivos metodos *get()* e *set()*.

Fora essas funcionalidades também é possível remover uma propriedade que foi registrado anteriormente.

***Removendo propriedades***
```php
<?php

use KeilielOliveira\Exception\Exception;

require_once 'vendor/autoload.php';

try {
    
    $e = new Exception();
    $e->message = 'Ocorreu um erro!';
    $e::remove('message');

    throw new Exception();

} catch (Exception $e) {
    echo $e->getMessage();
}
```

Se tentar usar o parametro *$e->message* agora, um erro será lançado pois ele não existe mais, uma observação é que não há um metodo *update()* e se tentar substituir um valor com o *set()* um erro será lançado, então a unica forma de substituir um valor é removendo ele antes de tentar redefinilo.

Outra funcionalidade é *clear()*, a classe armazena todas as propriedades de forma estatica para assim permitir uma flexibilidade maior na definição de propriedades para o contexto da exceção, quando essas propriedades não forem mais uteis, utilize esse metodo.

***Limpando as propriedades:***
```php
<?php

use KeilielOliveira\Exception\Exception;

require_once 'vendor/autoload.php';

try {
    
    $e = new Exception();
    $e->message = 'Ocorreu um erro!';
    $e->code = 10;
    $e->errorType = 'Erro generico.';

    $e::clear();

    throw new Exception();

} catch (Exception $e) {
    echo $e->getMessage();
}
```

O metodo *clear()* simplesmente deleta todas as propriedades definidas anteriormente permitindo assim começar um novo contexto.

E a ultima funcionalidade da biblioteca é a geração automatica de mensagens com base em templates e dados.

***Geração de mensagens:***
```php
<?php

use KeilielOliveira\Exception\Exception;

require_once 'vendor/autoload.php';

try {
    
    Exception::set('type', 'erro generico', true);
    Exception::createMessage('Ocorreu um erro do tipo @type');

    throw new Exception();

} catch (Exception $e) {
    echo $e->getMessage(); //Ocorreu um erro do tipo erro generico
}
```

O que o metodo *createMessage()* faz, é criar uma mensagem com base no template e os dados passados, o metodo *set()* possui um terceiro parametro que por padrão é false, quando definido como **true** essa propriedade será armazenada em um array que fica em uma chave reservada, quando o metodo *createMessage()* é chamado ele substitui as marcações *@nomeDaPropriedade* pela propriedade definida com o terceiro parametro sendo **true**, o metodo também já defini essa mensagem para ser enviada no lançamento da exceção, mas também é possível passar uma mensagem diferente, a mensagem passada no lançamento da exceção tem prioridade sobre a gerada automaticamente.

Caso queira recuperar a mensagem gerada automaticamente é possivel de duas formas.

***Recuperando a mensagem:***
```php
<?php

use KeilielOliveira\Exception\Exception;

require_once 'vendor/autoload.php';

try {
    
    Exception::set('type', 'erro generico', true);
    Exception::createMessage('Ocorreu um erro do tipo @type');

    $e = new Exception();
    $message = $e->getMessage();

    // OU

    $message = Exception::self()->getMessage();

    throw new Exception($message);

} catch (Exception $e) {
    echo $e->getMessage();
}
```

Isso abrange todas as funcionalidades da biblioteca.