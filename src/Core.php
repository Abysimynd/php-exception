<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

/**
 * Núcleo da biblioteca, contém todos os métodos para o uso da mesma.
 */
class Core {
    public function __construct() {
        Container::getContainer()->set( InstanceControl::class );
        Container::getContainer()->set( Data::class );
    }

    /**
     * Defini uma instancia permanente que será usada em todas as operações seguintes até que a mesma seja
     * explicitamente trocada.
     *
     * Documentação: https://github.com/Abysimynd/php-exception/blob/master/README.md
     */
    public function setInstance( string $instance ): void {
        $control = Container::getContainer()->get( InstanceControl::class );
        $control->setInstance( $instance );
    }

    /**
     * Defini uma instancia temporária que será usada somente na próxima operação, após irá ser automaticamente
     * removida e a instancia definida passará a ser a ultima instancia permanente definida.
     *
     * Somente os métodos de controle de dados são considerados operações, portanto somente após o uso de um
     * desses métodos que a instancia temporária sera removida.
     *
     * Documentação: https://github.com/Abysimynd/php-exception/blob/master/README.md
     */
    public function setTempInstance( string $instance ): self {
        $control = Container::getContainer()->get( InstanceControl::class );
        $control->setInstance( $instance, true );

        return $this;
    }

    /**
     * Retorna a ultima instancia permanente definida ou não se nenhuma tiver sido definida.
     *
     * Documentação: https://github.com/Abysimynd/php-exception/blob/master/README.md
     */
    public function getInstance(): ?string {
        $control = Container::getContainer()->get( InstanceControl::class );

        return $control->getInstance();
    }

    /**
     * Retorna a ultima instancia temporária definida ou null caso nenhuma seja definida.
     *
     * Documentação: https://github.com/Abysimynd/php-exception/blob/master/README.md
     */
    public function getTempInstance(): ?string {
        $control = Container::getContainer()->get( InstanceControl::class );

        return $control->getInstance( true );
    }

    /**
     * Retorna a instancia que será usada na próxima operação ou null caso nenhuma tenha sido definida.
     *
     * As instancias temporárias sempre terão prioridade se definidas.
     *
     * Documentação: https://github.com/Abysimynd/php-exception/blob/master/README.md
     */
    public function getDefinedInstance(): ?string {
        $control = Container::getContainer()->get( InstanceControl::class );

        return $control->getDefinedInstance();
    }

    /**
     * Salva o valor na chave dentro da instancia definida atualmente.
     *
     * A chave pode ser uma chave simples ou conter índices como um array através de uma sintaxe especifica
     * a mesma pode ser encontrada na documentação.
     *
     * As chaves podem ser qualquer conjunto de caracteres com exceção de alguns, também há uma lista de chaves
     * reservadas ao sistema que não poderão ser usadas, as especificações podem ser encontradas na
     * documentação.
     *
     * Caso a chave recebida já exista, os valores antigos e os atuais serão mesclados em um novo array
     * contendo ambos, a explicação completa pode ser encontrada na documentação.
     *
     * documentação: https://github.com/Abysimynd/php-exception/blob/master/README.md
     */
    public function set( int|string $key, mixed $value ): void {
        [$key, $instance, $data] = new PrepareCoreArgs( $key )->getArgs();

        $control = new DataControl( $data );
        $preparedData = $control->set( $key, $value );

        $data = Container::getContainer()->get( Data::class );
        $data->setData( $instance, $preparedData );
    }

    /**
     * Atualiza o valor da chave pelo recebido dentro da instancia definida atualmente.
     *
     * A chave pode ser uma chave simples ou conter índices como um array através de uma sintaxe especifica
     * a mesma pode ser encontrada na documentação.
     *
     * As chaves podem ser qualquer conjunto de caracteres com exceção de alguns, também há uma lista de chaves
     * reservadas ao sistema que não poderão ser usadas, as especificações podem ser encontradas na documentação.
     *
     * Caso a chave recebida não seja encontrada na instancia atual, uma exceção será lançada, para saber mais
     * sobre o método leia  a documentação.
     *
     * documentação: https://github.com/Abysimynd/php-exception/blob/master/README.md
     */
    public function update( int|string $key, mixed $value ): void {
        [$key, $instance, $data] = new PrepareCoreArgs( $key )->getArgs();

        $control = new DataControl( $data );
        $preparedData = $control->update( $key, $value );

        $data = Container::getContainer()->get( Data::class );
        $data->setData( $instance, $preparedData );
    }

    /**
     * Remove o valor e a própria chave da instancia definida atualmente.
     *
     * A chave pode ser uma chave simples ou conter índices como um array através de uma sintaxe especifica
     * a mesma pode ser encontrada na documentação.
     *
     * As chaves podem ser qualquer conjunto de caracteres com exceção de alguns, também há uma lista de chaves
     * reservadas ao sistema que não poderão ser usadas, as especificações podem ser encontradas na documentação.
     *
     * Caso a chave recebida não seja encontrada na instancia atual, uma exceção será lançada, para saber mais
     * sobre o método leia  a documentação.
     *
     * documentação: https://github.com/Abysimynd/php-exception/blob/master/README.md
     */
    public function remove( int|string $key ): void {
        [$key, $instance, $data] = new PrepareCoreArgs( $key )->getArgs();

        $control = new DataControl( $data );
        $preparedData = $control->remove( $key );

        $data = Container::getContainer()->get( Data::class );
        $data->setData( $instance, $preparedData );
    }

    /**
     * Retorna o valor contido na chave dentro da instancia definida atualmente.
     *
     * Caso a chave seja passada como null (valor padrão), todos os dados contidos na instancia atual serão
     * retornados.
     *
     * A chave pode ser uma chave simples ou conter índices como um array através de uma sintaxe especifica
     * a mesma pode ser encontrada na documentação.
     *
     * As chaves podem ser qualquer conjunto de caracteres com exceção de alguns, também há uma lista de chaves
     * reservadas ao sistema que não poderão ser usadas, as especificações podem ser encontradas na documentação.
     *
     * Caso a chave recebida não seja encontrada na instancia atual, uma exceção será lançada, para saber mais
     * sobre o método leia  a documentação.
     *
     * documentação: https://github.com/Abysimynd/php-exception/blob/master/README.md
     */
    public function get( null|int|string $key = null ): mixed {
        [$key, $instance, $data] = new PrepareCoreArgs( $key )->getArgs();

        $control = new DataControl( $data );

        return $control->get( $key );
    }

    /**
     * Limpa todos os dados da instancia definida atualmente.
     *
     * A própria instancia é removida, porém isso não afeta diretamente seu uso futuramente, para maiores
     * detalhes leia  a documentação.
     *
     * documentação: https://github.com/Abysimynd/php-exception/blob/master/README.md
     */
    public function clearInstanceData(): void {
        $control = Container::getContainer()->get( InstanceControl::class );
        $instance = $control->getValidInstance();

        $control = Container::getContainer()->get( Data::class );
        $control->clearData( $instance );
    }

    /**
     * Remove todas as instancia e seus respectivos dados.
     *
     * Mesmo que as instancias sejam removidas, seu uso futuro não é comprometido, para maiores detalhes
     * leia  a documentação.
     *
     * documentação: https://github.com/Abysimynd/php-exception/blob/master/README.md
     */
    public function clearAllData(): void {
        $control = Container::getContainer()->get( Data::class );
        $control->clearData( null );
    }

    /**
     * Cria uma mensagem com base no template passado, dados salvos e backtrace.
     *
     * O template pode conter marcações que serão substituídos pelos seus respectivos valores em string,
     * para compreender melhor a sintaxe dos templates e as especificações das marcações, leia  a documentação.
     *
     * documentação: https://github.com/Abysimynd/php-exception/blob/master/README.md
     */
    public function buildMessage( string $template ): string {
        $builder = new BuildMessage( $template );

        return $builder->getMessage();
    }
}
