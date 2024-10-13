<?php

namespace App\Helpers;

use Carbon\Carbon;

class TCMBFetchDayRow
{
    public function __construct(
        public Carbon $date,
        public string $url,
    ) {}

    public function __toString(): string
    {
        return sprintf("%s, %s", $this->date, $this->url);
    }

    public static function make(Carbon $date, string $url): self
    {
        return new self($date, $url);
    }
}
