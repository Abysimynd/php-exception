# Instancias

As instancias são basicamente chaves que referenciam onde as operações seguintes serão realizadas, elas agem como se você estivesse acessando uma nova instancia da classe.

As instancias podem ser divididas em duas categorias, **permanentes** e **temporárias**, as instancias permanentes como o nome diz, permanecem definidas até que a mesma seja explicitamente alterada, já as instancias temporárias, só serão definidas para a próxima operação, após isso elas são automaticamente removidas e a instancia definida passa a ser a ultima instancia permanente definida anteriormente.

## Métodos de controle

Há diversos métodos para controlar as instancias e abaixo segue-se a lista dos mesmos com suas definições.

### use()

Este método defini uma instancia permanente.

#### parâmetros:

- string **$instance**: A instancia a ser definida.

### in()

Este método defini uma instancia temporária.

#### parâmetros:

- string **$instance**: A instancia a ser definida.

### getInstance()

Este método retorna a ultima instancia permanente definida ou **null** caso nenhuma tenha sido definida.

### getTempInstance()

Este método retorna a ultima instancia temporária definida ou **null** caso nenhuma tenha sido definida. Um ponto importante é que este método não conta como uma operação, então a instancia temporária não sera removida após ele.

### getDefinedInstance()

Este método retorna a instancia que será usada na próxima operação ou **null** caso nem uma instancia permanente ou uma instancia temporária esteja definida. A instancia temporária sempre tera prioridade se definida, este método não conta como uma operação, então a instancia temporária não sera removida após ele.

---

Esses são os métodos de manipulação de instancias, vale destacar que, o primeiro método obrigatório a ser chamado é um dos dois métodos de definição de instancias, sem uma instancia definida, as operações não poderão ser concluídas e uma exceção será lançada.

Também é importante se ater aos nomes das instancias, embora as regras sejam bem simples, não podem conter **{}**, **[]** ou **()**.