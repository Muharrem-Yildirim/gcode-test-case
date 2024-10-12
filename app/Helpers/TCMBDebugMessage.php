<?php

namespace App\Helpers;

use App\Enums\TCMBDebugMessageTypesEnum;


class TCMBDebugMessage
{
    public function __construct(
        public TCMBDebugMessageTypesEnum $type,
        public string $date,
        public string $message = "",
    ) {}

    public function __toString(): string
    {
        return sprintf("[%s] Date: %s, Message: %s", $this->type, $this->date, $this->message);
    }
}
