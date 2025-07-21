<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

use KeilielOliveira\Exception\Config\Config;
use KeilielOliveira\Exception\Data\DataControl;
use KeilielOliveira\Exception\Instances\InstanceControl;

/**
 * Gerencia os dados usados para contextualizar exceções.
 */
class Data {

    // Instancias de dependências da classe.
    private Config $config;
    private InstanceControl $instanceControl;
    private DataControl $dataControl;

    /**
     * Inicia as dependências.
     */
    public function __construct() {
        $this->config = new Config();
        $this->instanceControl = new InstanceControl();
        $this->dataControl = new DataControl(
            $this->config,
            $this->instanceControl
        );
    }

    /**
     * Atualiza as configurações padrões para as recebidas.
     *
     * @param array<mixed> $config
     */
    public function config( array $config ): void {
        $this->config->setConfig( $config );
    }

    /**
     * Defini a instancia/chave que será o referencial usado para realizar as operações seguintes.
     *
     * Para realizar uma operação uma instancia deve estar definida ou uma exceção será lançada.
     */
    public function use( string $instance ): void {
        $this->instanceControl->setInstance( $instance );
    }

    /**
     * Defini a instancia/chave que será o referencial usado para realizar a operação seguinte.
     *
     * Está instancia é temporária e só será usada na próxima operação, após isso ela será deletada.
     * Para realizar uma operação uma instancia deve estar definida ou uma exceção será lançada.
     */
    public function in( string $instance ): self {
        $this->instanceControl->setInstance( $instance, true );

        return $this;
    }

    /**
     * Retorna a instancia atual ou null se não houver uma.
     */
    public function getInstance(): ?string {
        return $this->instanceControl->getInstance();
    }

    /**
     * Retorna a instancia temporária atual ou null se não houver uma.
     */
    public function getTempInstance(): ?string {
        return $this->instanceControl->getInstance( true );
    }

    /**
     * Retorna a instancia definida para a próxima operação ou null se não houver uma.
     *
     * A instancia temporária sempre tera prioridade se definida.
     */
    public function getDefinedInstance(): ?string {
        return $this->instanceControl->getDefinedInstance();
    }

    /**
     * Salva o valor na chave dentro da instancia atual.
     */
    public function set( int|string $key, mixed $value ): void {
        $this->dataControl->setData( $key, $value );
    }

    /**
     * Atualiza o valor na chave dentro da instancia atual.
     *
     * Caso a chave não seja encontrada uma exceção será lançada.
     */
    public function update( int|string $key, mixed $value ): void {
        $this->dataControl->updateData( $key, $value );
    }

    /**
     * Remove a chave e seu valor de dentro da instancia atual.
     *
     * Caso a chave não seja encontrada uma exceção será lançada.
     */
    public function remove( int|string $key ): void {
        $this->dataControl->removeData( $key );
    }

    /**
     * Retorna o valor da chave na instancia atual ou todos os dados da instancia se a chave for null.
     */
    public function get( null|int|string $key = null ): mixed {
        return $this->dataControl->getData( $key );
    }

    /**
     * Limpa todos os dados da instancia atual.
     */
    public function clearInstance(): void {
        $this->dataControl->clearData();
    }

    /**
     * Limpa todos os dados de todas as instancias.
     */
    public function clearData(): void {
        $this->dataControl->clearData( true );
    }
}
