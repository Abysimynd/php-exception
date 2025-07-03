<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

enum ExceptionCodes: int {
    case UNKNOW_CORE_ERROR = 0;
    case MISSING_INSTANCE = 1;
    case INSTANCE_NOT_EXIST = 2;
    case KEY_ALREADY_DEFINED = 3;
}
