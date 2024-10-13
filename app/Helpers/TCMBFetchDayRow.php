<?php

namespace App\Helpers;

use Carbon\Carbon;

class TCMBFetchDayRow
{
    public function __construct(
        public Carbon $date,
        public string $path,
    ) {}

    public function __toString(): string
    {
        return sprintf("%s, %s", $this->date, $this->path);
    }

    public static function make(Carbon $date, string $path): self
    {
        return new self($date, $path);
    }
}
