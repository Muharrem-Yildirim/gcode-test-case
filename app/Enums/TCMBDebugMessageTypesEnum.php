<?php

declare(strict_types=1);

namespace App\Enums;


enum TCMBDebugMessageTypesEnum: string
{
    case UNKNOWN = "UNKNOWN";
    case CACHED = "CACHED";
    case WEEKEND = "WEEKEND";
    case AFTER_TODAY = "AFTER_TODAY";
}
