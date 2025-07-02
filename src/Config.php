<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

enum Config: string {
    case TEMPLATE_DATA_KEY = '__template_message_data';
    case TEMPLATE_DATA_PATTERN = '/@[a-zA-Z0-9_]+/';
    case EXCEPTION_MESSAGE_KEY = '__exception_message';
}
