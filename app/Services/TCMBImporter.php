<?php

namespace App\Services;

use App\Enums\TCMBDebugMessageTypesEnum;
use App\Helpers\TCMBDataOfDay;
use App\Helpers\TCMBDebugMessage;
use App\Helpers\TCMBFetchDayRow;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class TCMBImporter
{
    public  const API_URL = 'https://www.tcmb.gov.tr/kurlar/';
    public  const CACHE_XML_PATTERN = 'tcmb_xml_%s';
    private Collection $debugMessages;
    private Collection $fetchedTCMBData;


    public function __construct(
        private ?Carbon $startDate = null,
        private ?Carbon $endDate = null
    ) {
        $this->debugMessages = collect();
        $this->fetchedTCMBData = collect();
    }

    public function setDateRange(Carbon $startDate, Carbon $endDate = null): self
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        return $this;
    }

    public function generateFetchUrls(): Collection
    {
        // https://www.tcmb.gov.tr/kurlar/202410/09102024.xml
        // https://www.tcmb.gov.tr/kurlar/today.xml

        $fetchUrls = collect();

        $days = $this->endDate == null ? 0 : $this->startDate->diffInDays($this->endDate);


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


            $finalUrl = self::API_URL . '/' . $date->format('Ym') . '/' . $date->format('dmY') . '.xml';

            $fetchUrls->push(TCMBFetchDayRow::make(
                $date,
                $finalUrl
            ));
        }

        return $fetchUrls;
    }

    public function fetch(): self
    {
        $fetchUrls = $this->generateFetchUrls();

        $fetchUrls->each(function (TCMBFetchDayRow $row) {
            if (Cache::has(sprintf(self::CACHE_XML_PATTERN, $row->date->format('dmY')))) {
                $xml = simplexml_load_string(
                    Cache::get(sprintf(self::CACHE_XML_PATTERN, $row->date->format('dmY')))
                );
            } else {
                $contents = file_get_contents($row->url);
                $xml = simplexml_load_string($contents);
                Cache::put(sprintf(self::CACHE_XML_PATTERN, $row->date->format('dmY')), $contents);
            }

            $header = xmlToCollection($xml, '@attributes');

            $this->fetchedTCMBData->push(TCMBDataOfDay::make(
                Carbon::parse($header['Date']),
                xmlToCollection($xml, 'Currency')
            ));
        });

        return $this;
    }

    public function store(): int
    {
        $count = $this->fetchedTCMBData->reduce(function (int $carry, TCMBDataOfDay $data) {
            return $carry + $data->store();
        }, 0);

        return $count;
    }
}
