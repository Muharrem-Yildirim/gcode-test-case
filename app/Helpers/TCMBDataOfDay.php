<?php

namespace App\Helpers;

use App\Models\Currency;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TCMBDataOfDay
{
    public const CACHE_IMPORTED_PATTERN = 'tcmb_imported_%s';

    public function __construct(private Carbon $date, private Collection $rows) {}


    public static function make(Carbon $date, Collection $rows)
    {
        return new self($date, $rows);
    }

    public function hasImportedBefore(): bool
    {
        return Cache::has(sprintf(self::CACHE_IMPORTED_PATTERN, $this->date->format('dmY')));
    }

    public function store()
    {
        if ($this->hasImportedBefore()) {
            return 0;
        }

        DB::beginTransaction();

        foreach ($this->rows as $row) {
            Currency::upsert([
                'date' => $this->date->toDateString(),
                'name' => $row['CurrencyName'],
                'code' => $row['@attributes']['CurrencyCode'],
                'forex_buying' => empty($row['ForexBuying']) ? null : $row['ForexBuying'],
                'forex_selling' => empty($row['ForexSelling']) ? null : $row['ForexSelling'],
                'banknote_buying' => empty($row['BanknoteBuying']) ? null : $row['BanknoteBuying'],
                'banknote_selling' => empty($row['BanknoteSelling']) ? null : $row['BanknoteSelling'],
                'cross_rate_usd' => empty($row['CrossRateUSD']) ? null : $row['CrossRateUSD'],
                'cross_rate_other' => empty($row['CrossRateOther']) ? null : $row['CrossRateOther'],
                'unit' => $row['Unit'],
            ], [
                'date',
                'code',
            ]);
        }

        DB::commit();

        Cache::put(sprintf(self::CACHE_IMPORTED_PATTERN, $this->date->format('dmY')), true);

        return $this->rows->count();
    }
}
