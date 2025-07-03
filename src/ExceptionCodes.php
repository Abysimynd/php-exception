<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

enum ExceptionCodes: int {
    case UNKNOW_CORE_ERROR = 0;
    case KEY_ALREADY_DEFINED = 1;
}
