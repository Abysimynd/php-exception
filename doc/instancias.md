# Instancias

As instancias são a base do uso dessa biblioteca, elas oferecem uma forma de organizar os dados armazenados logicamente, como se fossem objetos diferentes.

# Métodos

Aqui estão listados todos os métodos de controle de instancias e suas descrições.

## setInstance()

Este é o primeiro método a ser usado, ele defini uma instancia permanente que irá permanecer definida em todas as operações futuras a não ser que seja explicitamente trocada chamando o método e definindo uma nova instancia para uso.

### parâmetros:

- *string* **$instance**: O nome da instancia a ser definida.

### retorno:

Este método retorna *void*.

### exceções:

- **InstanceException**: quando a sintaxe da instancia é invalida.

### observações:

Não há nenhuma observação referente a este método.

### exemplos de uso:
```php
<?php

use KeilielOliveira\Exception\CoreFacade;

require_once 'vendor/autoload.php';

CoreFacade::setInstance( 'instance_A' );

// Todas as operações a partir daqui serão realizadas na instancia instance_A.

CoreFacade::setInstance( 'instance_B' );

// A partir desse ponto, a nova instancia definida é a instance_B.
```

## setTempInstance()

Assim como o método ***setInstance()***, este método defini uma instancia, a única diferença é que a instancia definida por ele é temporária, ela só sera usada na próxima operação, após isso a instancia definida voltara a ser a ultima instancia definida permanentemente.

### parâmetros:

- *string* **$instance**: O nome da instancia a ser definida.

### retorno:

Este método retorna o **$this**, permitindo assim encadear a operação logo em seguida.

### exceções:

- **InstanceException**: quando a sintaxe da instancia é invalida.

### observações:

A utilidade desse método se encontra na possibilidade de realizar uma operação em outra instancia sem comprometer o fluxo da instancia atual.

### exemplos de uso:
```php
<?php

use KeilielOliveira\Exception\CoreFacade;

require_once 'vendor/autoload.php';

CoreFacade::setInstance( 'instance_A' );

// Todas as operações a partir daqui serão realizadas na instancia instance_A.

CoreFacade::setInstance( 'instance_B' );

// A partir desse ponto, a nova instancia definida é a instance_B.

// Isso permite realizar uma operação na instancia instance_A.
CoreFacade::setTempInstance('instance_A');
```

## getInstance()

Este método recupera a instancia permanente definida atualmente.

### parâmetros:

Não há parâmetros.

### retorno:

Este método retorna uma *string* se encontrar uma instancia permanente definida e *null* se não houver uma instancia permanente definida.

### exceções:

Nenhuma exceção é lançada.

### observações:

Não há nenhuma observação referente a este método.

### exemplos de uso:
```php
<?php

use KeilielOliveira\Exception\CoreFacade;

require_once 'vendor/autoload.php';

CoreFacade::getInstance(); // Retorna null
CoreFacade::setInstance( 'instance_A' );
CoreFacade::getInstance(); // Retorna instance_A
```

## getTempInstance()

Este método recupera a instancia temporária definida atualmente.

### parâmetros:

Não há parâmetros.

### retorno:

Este método retorna uma *string* se encontrar uma instancia permanente definida e *null* se não houver uma instancia permanente definida.

### exceções:

Nenhuma exceção é lançada.

### observações:

Este método não conta como uma operação, portanto, usa-lo não irá remover a instancia temporária.

### exemplos de uso:
```php
<?php

use KeilielOliveira\Exception\CoreFacade;

require_once 'vendor/autoload.php';

CoreFacade::getInstance(); // Retorna null
CoreFacade::setInstance( 'instance_A' );
CoreFacade::getInstance(); // Retorna instance_A
```

## getDefinedInstance()

Retorna a instancia que será usada na próxima operação.

### parâmetros:

Não há parâmetros.

### retorno:

Retorna uma *string* se houver uma instancia permanente ou temporária definida e *null* se não houver nenhuma instancia definida.

### exceções:

Nenhuma exceção é lançada.

### observações:

As instancias temporárias sempre terão prioridade sobre as instancias permanentes.

### exemplos de uso:
```php
<?php

use KeilielOliveira\Exception\CoreFacade;

require_once 'vendor/autoload.php';

CoreFacade::getDefinedInstance(); // Retorna null
CoreFacade::setInstance( 'instance_A' );
CoreFacade::getDefinedInstance(); // Retorna instance_A
CoreFacade::setTempInstance('temporary_instance');
CoreFacade::getDefinedInstance(); // Retorna temporary_instance
```

# Sintaxe das instancias

As instancias devem seguir uma sintaxe especifica, embora não seja restritiva. As instancias devem ser qualquer conjunto de caracteres com exceção dos listados a seguir: **{}**, **[]** e **()**

Com exceção desses caracteres, qualquer outro é permitido, não há limitações quanto ao tamanho da *string* que a compõe também, portanto deve-se atentar e levar isso em conta para o desempenho.