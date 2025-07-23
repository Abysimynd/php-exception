<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

use KeilielOliveira\Exception\Config\Config;
use KeilielOliveira\Exception\Data\DataControl;
use KeilielOliveira\Exception\Instances\InstanceControl;
use KeilielOliveira\Exception\Message\MessageBuilder;

/**
 * Gerencia os dados usados para contextualizar exceções.
 */
class Core {
    /**
     * Inicia as dependências.
     */
    public function __construct() {
        $container = Container::getContainer();
        $container->set( Config::class );
        $container->set( InstanceControl::class );
        $container->set( DataControl::class );
        $container->set( MessageBuilder::class );
    }

    /**
     * Atualiza as configurações padrões para as recebidas.
     *
     * @param array<mixed> $config
     */
    public function config( array $config ): void {
        Container::getContainer()
            ->get( Config::class )->setConfig( $config )
        ;
    }

    /**
     * Defini a instancia/chave que será o referencial usado para realizar as operações seguintes.
     *
     * Para realizar uma operação uma instancia deve estar definida ou uma exceção será lançada.
     */
    public function use( string $instance ): void {
        Container::getContainer()
            ->get( InstanceControl::class )->setInstance( $instance )
        ;
    }

    /**
     * Defini a instancia/chave que será o referencial usado para realizar a operação seguinte.
     *
     * Está instancia é temporária e só será usada na próxima operação, após isso ela será deletada.
     * Para realizar uma operação uma instancia deve estar definida ou uma exceção será lançada.
     */
    public function in( string $instance ): self {
        Container::getContainer()
            ->get( InstanceControl::class )->setInstance( $instance, true )
        ;

        return $this;
    }

    /**
     * Retorna a instancia atual ou null se não houver uma.
     */
    public function getInstance(): ?string {
        return Container::getContainer()
            ->get( InstanceControl::class )->getInstance()
        ;
    }

    /**
     * Retorna a instancia temporária atual ou null se não houver uma.
     */
    public function getTempInstance(): ?string {
        return Container::getContainer()
            ->get( InstanceControl::class )->getInstance( true )
        ;
    }

    /**
     * Retorna a instancia definida para a próxima operação ou null se não houver uma.
     *
     * A instancia temporária sempre tera prioridade se definida.
     */
    public function getDefinedInstance(): ?string {
        return Container::getContainer()
            ->get( InstanceControl::class )->getDefinedInstance()
        ;
    }

    /**
     * Salva o valor na chave dentro da instancia atual.
     */
    public function set( int|string $key, mixed $value ): void {
        Container::getContainer()
            ->get( DataControl::class )->setData( $key, $value )
        ;

        Container::getContainer()
            ->get( InstanceControl::class )->clearTempInstance()
        ;
    }

    /**
     * Atualiza o valor na chave dentro da instancia atual.
     *
     * Caso a chave não seja encontrada uma exceção será lançada.
     */
    public function update( int|string $key, mixed $value ): void {
        Container::getContainer()
            ->get( DataControl::class )->updateData( $key, $value )
        ;

        Container::getContainer()
            ->get( InstanceControl::class )->clearTempInstance()
        ;
    }

    /**
     * Remove a chave e seu valor de dentro da instancia atual.
     *
     * Caso a chave não seja encontrada uma exceção será lançada.
     */
    public function remove( int|string $key ): void {
        Container::getContainer()
            ->get( DataControl::class )->removeData( $key )
        ;

        Container::getContainer()
            ->get( InstanceControl::class )->clearTempInstance()
        ;
    }

    /**
     * Retorna o valor da chave na instancia atual ou todos os dados da instancia se a chave for null.
     */
    public function get( null|int|string $key = null ): mixed {
        $data = Container::getContainer()
            ->get( DataControl::class )->getData( $key )
        ;

        Container::getContainer()
            ->get( InstanceControl::class )->clearTempInstance()
        ;

        return $data;
    }

    /**
     * Limpa todos os dados da instancia atual.
     */
    public function clearInstance(): void {
        Container::getContainer()
            ->get( DataControl::class )->clearData()
        ;

        Container::getContainer()
            ->get( InstanceControl::class )->clearTempInstance()
        ;
    }

    /**
     * Limpa todos os dados de todas as instancias.
     */
    public function clearData(): void {
        Container::getContainer()
            ->get( DataControl::class )->clearData()
        ;
    }

    /**
     * Cria uma mensagem com base no template passado e nos dados salvos.
     */
    public function createMessage( string $template ): string {
        return Container::getContainer()->get( MessageBuilder::class )
            ->buildMessage( $template )
        ;
    }
}
