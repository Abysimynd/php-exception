<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

enum ExceptionCodes: int {
    case UNKNOW_INTERNAL_ERROR = 0;
    case PROPERTY_ALREADY_DEFINED = 1;
    case PROPERTY_NOT_FOUND = 2;
    case INVALID_PROPERTY_NAME = 3;

    case UNKNOW_MESSAGE_TEMPLATE_ERROR = 10;
    case TEMPLATE_DATA_NOT_DEFINED = 11;
    case MISSING_TEMPLATE_DATA = 12;
    case INVALID_REPLACEMENT_VALUE = 13;
}
