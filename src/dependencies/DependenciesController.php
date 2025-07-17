<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Dependencies;

class DependenciesController {
    /** @var DependenciesContainer Container contendo todas as instancias */
    private DependenciesContainer $container;

    /** @var object Classe a ser instanciada */
    private object $dependencie;

    /**
     * Cria a instancia da classe passada.
     *
     * @param mixed[] $args
     */
    public function __construct( string $class, DependenciesContainer $container, mixed ...$args ) {
        $this->container = $container;
        $this->initDependencie( $class, ...$args );
    }

    /**
     * Retorna a instancia da classe.
     */
    public function getDependencie(): object {
        return $this->dependencie;
    }

    /**
     * Inicia a criação da instancia da classe.
     *
     * @param mixed[] $args
     */
    private function initDependencie( string $class, mixed ...$args ): void {
        $this->dependencie = $this->createObject( $class, ...$args );
        $this->injectContainer();
    }

    /**
     * Cria a instancia da classe passando os parametros se existirem.
     *
     * @param mixed[] $args
     */
    private function createObject( string $class, mixed ...$args ): object {
        if ( !empty( $args ) ) {
            return new $class( ...$args );
        }

        return new $class();
    }

    /**
     * Injeta o container caso haja uma propriedade que recebe ele na classe sendo instanciada.
     */
    private function injectContainer(): void {
        $reflectionClass = new \ReflectionClass( $this->dependencie );
        $containerProperty = $this->requireContainer( $reflectionClass );

        if ( !$containerProperty ) {
            return;
        }

        $containerProperty = $reflectionClass->getProperty( $containerProperty );
        $containerProperty->setAccessible( true );
        $containerProperty->setValue( $this->dependencie, $this->container );
    }

    /**
     * Verifica se a classe sendo instanciada requer o container.
     *
     * @param \ReflectionClass<object> $reflectionClass
     */
    private function requireContainer( \ReflectionClass $reflectionClass ): false|string {
        $properties = $reflectionClass->getProperties();

        foreach ( $properties as $key => $property ) {
            /** @var \ReflectionProperty $property */
            $propertyType = $property->getType();

            if ( null != $propertyType ) {
                if ( $propertyType instanceof \ReflectionUnionType ) {
                    continue;
                }

                /** @var \ReflectionNamedType $propertyType */
                if ( DependenciesContainer::class == $propertyType->getName() ) {
                    return $property->getName();
                }
            }
        }

        return false;
    }
}
