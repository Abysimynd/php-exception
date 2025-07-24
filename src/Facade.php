<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

class Facade {
    private static Core $core;

    protected function __construct() {
        self::$core = self::$core ?? new Core();
    }

    /**
     * Atualiza as configurações padrões para as recebidas.
     *
     * @param array<mixed> $config
     */
    public static function config( array $config ): void {
        new self();
        self::$core->config( $config );
    }

    /**
     * Defini a instancia/chave que será o referencial usado para realizar as operações seguintes.
     *
     * Para realizar uma operação uma instancia deve estar definida ou uma exceção será lançada.
     */
    public static function use( string $instance ): void {
        new self();
        self::$core->use( $instance );
    }

    /**
     * Defini a instancia/chave que será o referencial usado para realizar a operação seguinte.
     *
     * Está instancia é temporária e só será usada na próxima operação, após isso ela será deletada.
     * Para realizar uma operação uma instancia deve estar definida ou uma exceção será lançada.
     */
    public static function in( string $instance ): self {
        new self();
        self::$core->in( $instance );

        return new self();
    }

    /**
     * Retorna a instancia atual ou null se não houver uma.
     */
    public static function getInstance(): ?string {
        return self::$core->getInstance();
    }

    /**
     * Retorna a instancia temporária atual ou null se não houver uma.
     */
    public static function getTempInstance(): ?string {
        return self::$core->getTempInstance();
    }

    /**
     * Retorna a instancia definida para a próxima operação ou null se não houver uma.
     *
     * A instancia temporária sempre tera prioridade se definida.
     */
    public static function getDefinedInstance(): ?string {
        return self::$core->getDefinedInstance();
    }

    /**
     * Salva o valor na chave dentro da instancia atual.
     */
    public static function set( int|string $key, mixed $value ): void {
        self::$core->set( $key, $value );
    }

    /**
     * Atualiza o valor na chave dentro da instancia atual.
     *
     * Caso a chave não seja encontrada uma exceção será lançada.
     */
    public static function update( int|string $key, mixed $value ): void {
        self::$core->update( $key, $value );
    }

    /**
     * Remove a chave e seu valor de dentro da instancia atual.
     *
     * Caso a chave não seja encontrada uma exceção será lançada.
     */
    public static function remove( int|string $key ): void {
        self::$core->remove( $key );
    }

    /**
     * Retorna o valor da chave na instancia atual ou todos os dados da instancia se a chave for null.
     */
    public static function get( null|int|string $key = null ): mixed {
        return self::$core->get( $key );
    }

    /**
     * Limpa todos os dados da instancia atual.
     */
    public static function clearInstance(): void {
        self::$core->clearInstance();
    }

    /**
     * Limpa todos os dados de todas as instancias.
     */
    public static function clearData(): void {
        self::$core->clearData();
    }

    /**
     * Cria uma mensagem com base no template passado e nos dados salvos.
     */
    public static function createMessage( string $template ): string {
        return self::$core->createMessage( $template );
    }
}
