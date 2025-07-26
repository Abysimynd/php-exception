<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception\Interfaces;

interface ValidatorsInterface {
    public function validate(): bool;
}
