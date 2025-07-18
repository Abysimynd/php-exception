<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

use Exception;
use KeilielOliveira\Exception\Config\Config;
use KeilielOliveira\Exception\Data\DataControl;
use KeilielOliveira\Exception\Dependencies\DependenciesContainer;
use KeilielOliveira\Exception\Instances\InstanceControl;

class Data {
    /** @var DependenciesContainer Container de dependencias */
    private static DependenciesContainer $container;

    /**
     * Inicia as dependencias em um container estatico uma unica vez.
     */
    protected function __construct() {
        if ( !isset( self::$container ) ) {
            $container = new DependenciesContainer();
            $container->setDependecie( Config::class );
            $container->setDependecie( InstanceControl::class );
            $container->setDependecie( DataControl::class );
            self::$container = $container;
        }
    }

    /**
     * Defini configurações proprias para a execução.
     *
     * @param array<mixed> $config
     */
    public static function config( array $config ): void {
        new Data();
        self::$container->getDependencie( Config::class )->setConfig( $config );
    }

    /**
     * Defini a instancia/chave que será o referencial usado para realizar as operações seguintes.
     *
     * Para realizar uma operação uma instancia deve estar definida ou uma exceção será lançada.
     */
    public static function use( string $instance ): void {
        new Data();
        self::$container->getDependencie( InstanceControl::class )->setInstance( $instance );
    }

    /**
     * Defini a instancia/chave que será o referencial usado para realizar a operação seguinte.
     *
     * Está instancia é temporaria e só será usada na proxima operação, após isso ela será deletada.
     * Para realizar uma operação uma instancia deve estar definida ou uma exceção será lançada.
     */
    public static function in( string $instance ): self {
        $data = new Data();
        self::$container->getDependencie( InstanceControl::class )
            ->setInstance( $instance, true )
        ;

        return $data;
    }

    /**
     * Retorna a instancia atual ou null se não houver uma.
     */
    public static function getInstance(): ?string {
        self::hasContainer();
        return self::$container->getDependencie( InstanceControl::class )->getInstance();
    }

    /**
     * Retorna a instancia temporaria atual ou null se não houver uma.
     */
    public static function getTempInstance(): ?string {
        self::hasContainer();
        return self::$container->getDependencie( InstanceControl::class )->getInstance( true );
    }

    /**
     * Retorna a instancia definida para a proxima operação ou null se não houver uma.
     *
     * A instancia temporaria sempre tera prioridade se definida.
     */
    public static function getDefinedInstance(): ?string {
        self::hasContainer();
        return self::$container->getDependencie( InstanceControl::class )->getDefinedInstance();
    }

    /**
     * Salva o valor na chave dentro da instancia atual.
     */
    public static function set( int|string $key, mixed $value ): void {
        self::hasContainer();
        self::$container->getDependencie( DataControl::class )->setData( $key, $value );
    }

    /**
     * Atualiza o valor na chave dentro da instancia atual.
     *
     * Caso a chave não sejá encontrada uma exceção será lançada.
     */
    public static function update( int|string $key, mixed $value ): void {
        self::hasContainer();
        self::$container->getDependencie( DataControl::class )->updateData( $key, $value );
    }

    /**
     * Remove a chave e seu valor de dentro da instancia atual.
     *
     * Caso a chave não sejá encontrada uma exceção será lançada.
     */
    public static function remove( int|string $key ): void {
        self::hasContainer();
        self::$container->getDependencie( DataControl::class )->removeData( $key );
    }

    /**
     * Retorna o valor da chave na instancia atual ou todos os dados da instancia se a chave for null.
     */
    public static function get( null|int|string $key = null ): mixed {
        self::hasContainer();
        return self::$container->getDependencie( DataControl::class )->getData( $key );
    }

    /**
     * Limpa todos os dados da instancia atual.
     */
    public static function clearInstance(): void {
        self::hasContainer();
        self::$container->getDependencie( DataControl::class )->clearData();
    }

    /**
     * Limpa todos os dados de todas as instancias.
     */
    public static function clearData(): void {
        self::hasContainer();
        self::$container->getDependencie( DataControl::class )->clearData( true );
    }

    private static function hasContainer(): void {
        if(!isset($container)) {
            new Data();
        }
    }
}
