<?php

namespace Tests\Unit;

use App\Helpers\TCMBDataOfDay;
use App\Models\Currency;
use App\Services\TCMBImporter;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;

final class TCMBImporterTest extends TestCase
{
    private TCMBImporter $tcmbImporter;

    public function setUp(): void
    {
        parent::setUp();

        $this->tcmbImporter = app(TCMBImporter::class);
    }

    #[Test]
    public function it_returns_the_tcmb_fetcher_when_called(): void
    {
        $this->assertInstanceOf(TCMBImporter::class, $this->tcmbImporter);
    }

    #[Test]
    public function it_excludes_weekends_while_generating_urls(): void
    {
        // 1. Arrange
        $date1 = Carbon::createFromDate(2024, 10, 1);
        $date2 = Carbon::createFromDate(2024, 10, 13);

        // 2. Act
        $generatedUrls = $this->tcmbImporter->setDateRange($date1, $date2)->generateFetchUrls();

        // 3. Assert
        $this->assertCount(9, $generatedUrls);
    }

    #[Test]
    public function it_excludes_future_dates_while_generating_urls(): void
    {
        // 1. Arrange
        $date1 = Carbon::now()->subDays(value: 30)->startOfWeek(CarbonInterface::MONDAY);
        $date2 = $date1->clone()->addDays(14);

        // 2. Act
        $generatedUrls = $this->tcmbImporter->setDateRange($date1, $date2)->generateFetchUrls();

        // 3. Assert
        $this->assertCount(11, $generatedUrls);
    }

    #[Test]
    public function it_imports_the_data(): void
    {
        // 1. Arrange
        $date1 = Carbon::create(2024, 10, 10);
        $date2 = Carbon::create(2024, 10, 11);

        // 2. Act
        $this->tcmbImporter->setDateRange($date1, $date2)->fetch()->store();

        // 3. Assert
        $this->assertCount(46, Currency::get());
    }

    #[Test]
    public function it_caches_if_the_data_has_been_imported(): void
    {
        // 1. Arrange
        $date1 = Carbon::create(2024, 10, 10);
        $date2 = Carbon::create(2024, 10, 10);

        // 2. Act
        $this->tcmbImporter->setDateRange($date1, $date2)->fetch()->store();

        // 3. Assert
        $this->assertTrue(Cache::get(sprintf(TCMBDataOfDay::CACHE_IMPORTED_PATTERN, $date1->format('dmY'))));
    }

    #[Test]
    public function it_only_imports_start_date_if_end_date_is_not_provided(): void
    {
        // 1. Arrange
        $date1 = Carbon::create(2024, 10, day: 10);
        $date2 = null;

        // 2. Act
        $this->tcmbImporter->setDateRange($date1, $date2)->fetch()->store();

        // 3. Assert
        $this->assertCount(23, Currency::get());
    }
}
