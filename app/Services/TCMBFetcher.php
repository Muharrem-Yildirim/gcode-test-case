<?php

namespace App\Services;

use App\Enums\TCMBDebugMessageTypesEnum;
use App\Helpers\TCMBDebugMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TCMBFetcher
{
    private Collection $debugMessages;


    public function __construct(
        private ?Carbon $startDate = null,
        private ?Carbon $endDate = null
    ) {
        $this->debugMessages = collect();
    }

    public function setDateRange(Carbon $startDate, Carbon $endDate = null): self
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        return $this;
    }

    public function generateFetchUrls()
    {
        // https://www.tcmb.gov.tr/kurlar/202410/09102024.xml
        // https://www.tcmb.gov.tr/kurlar/today.xml

        $fetchUrls = collect();

        $days = $this->startDate->diffInDays($this->endDate);


        for ($i = 0; $i <= $days; $i++) {
            $date = $this->startDate->clone()->addDays($i);

            if ($date->isWeekend()) {
                $this->debugMessages->push(new TCMBDebugMessage(TCMBDebugMessageTypesEnum::WEEKEND, $date));
                continue;
            }

            if ($date->isAfter(Carbon::now())) {
                $this->debugMessages->push(new TCMBDebugMessage(TCMBDebugMessageTypesEnum::AFTER_TODAY, $date));
                break;
            }


            $fetchUrls->push('https://www.tcmb.gov.tr/kurlar/' . $date->format('Ym') . '/' . $date->format('dmY') . '.xml');
        }


        return $fetchUrls;
    }

    public function run()
    {
        $fetchUrls = $this->generateFetchUrls();

        $fetchUrls->each(function ($url) {
            $xml = simplexml_load_file($url);
            dump($xml);
        });
    }
}
