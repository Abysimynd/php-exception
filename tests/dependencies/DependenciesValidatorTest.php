<?php

declare(strict_types = 1);
use Dependencies\TestsContent\Class\ClassThree;
use KeilielOliveira\Exception\Dependencies\DependenciesValidator;
use KeilielOliveira\Exception\Dependencies\InvalidDependencieException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class DependenciesValidatorTest extends TestCase {
    public function testIsValidatingIfClassExists(): void {
        $class = 'Invalid\Class\Name';
        $message = "Não foi possível encontrar a classe {$class}.";

        $this->expectException( InvalidDependencieException::class );
        $this->expectExceptionMessage( $message );

        new DependenciesValidator( $class );
    }

    public function testIsValidationgIfHasRequiredParameters(): void {
        $class = ClassThree::class;
        $message = "A dependencia {$class} requer um minimo de 2, mas somente 0 foram passados.";

        $this->expectException( InvalidDependencieException::class );
        $this->expectExceptionMessage( $message );

        new DependenciesValidator( $class );
    }

    public function testIsValidatingIfMaxParametersExceeded(): void {
        $class = ClassThree::class;
        $message = "A dependencia {$class} recebe um maximo de 3, mas 4 foram passados.";

        $this->expectException( InvalidDependencieException::class );
        $this->expectExceptionMessage( $message );

        new DependenciesValidator( $class, 1, 2, 3, 4 );
    }
}
