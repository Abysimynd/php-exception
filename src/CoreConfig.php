<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

enum CoreConfig: string {
    case DATA_KEYS_PATTERN = '/([^\->]+)/';
}
