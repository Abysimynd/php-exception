<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

class ExceptionCore extends \Exception {
    private static ?string $instance;

    private static ?string $tempInstance;

    protected function __construct( ?string $instance, bool $useTemp = false ) {
        self::createInstance( $instance, $useTemp );
        new CoreData( $instance );
    }

    public static function use( string $instance ): self {
        return new ExceptionCore( $instance );
    }

    public static function in( string $instance ): self {
        return new ExceptionCore( $instance, true );
    }

    public static function getInstance(): ?string {
        return self::$instance;
    }

    public static function getTempInstance(): ?string {
        return self::$tempInstance;
    }

    public static function set( int|string $key, mixed $value ): void {
        $instance = self::getDefinedInstance();
        CoreData::set( $instance, $key, $value );
        self::clearTempInstance();
    }

    public static function update( int|string $key, mixed $value ): void {
        $instance = self::getDefinedInstance();
        $data = CoreData::get( $instance );
        $validator = new CoreValidator( $data, __METHOD__ );
        $validator->validateKey( $key );

        CoreData::update( $instance, $key, $value );
        self::clearTempInstance();
    }

    public static function remove( int|string $key ): void {
        $instance = self::getDefinedInstance();
        $data = CoreData::get( $instance );
        print_r( $data );
        echo '<hr>';
        $validator = new CoreValidator( $data );
        $validator->validateKey( $key );

        CoreData::remove( $instance, $key );
        self::clearTempInstance();
    }

    public static function get( null|int|string $key = null ): mixed {
        $instance = self::getDefinedInstance();
        $data = CoreData::get( $instance, $key );
        self::clearTempInstance();

        return $data;
    }

    public static function clear( ?string $instance = null ): void {
        CoreData::clear( $instance );
    }

    public static function clearCore(): void {
        self::$instance = null;
        self::clearTempInstance();
    }

    private static function createInstance( ?string $instance, bool $useTemp ): void {
        if ( $useTemp ) {
            self::$tempInstance = $instance;
        } else {
            self::$instance = $instance;
        }
    }

    private static function getDefinedInstance(): ?string {
        return self::$tempInstance ?? self::$instance;
    }

    private static function clearTempInstance(): void {
        self::$tempInstance = null;
    }
}
