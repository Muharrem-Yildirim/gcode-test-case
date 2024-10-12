<?php

namespace Tests\Unit;

use App\Services\TCMBFetcher;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Carbon\CarbonInterface;

final class TCMBFetcherTest extends TestCase
{
    private TCMBFetcher $tcmbFetcher;

    public function setUp(): void
    {
        parent::setUp();

        $this->tcmbFetcher = app(TCMBFetcher::class);
    }

    #[Test]
    public function it_returns_the_tcmb_fetcher_when_called(): void
    {
        $this->assertInstanceOf(TCMBFetcher::class, $this->tcmbFetcher);
    }

    #[Test]
    public function it_excludes_weekends_while_generating_urls(): void
    {
        // 1. Arrange
        $date1 = Carbon::createFromDate(2024, 10, 1);
        $date2 = Carbon::createFromDate(2024, 10, 13);

        // 2. Act
        $generatedUrls = $this->tcmbFetcher->setDateRange($date1, $date2)->generateFetchUrls();

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
        $generatedUrls = $this->tcmbFetcher->setDateRange($date1, $date2)->generateFetchUrls();

        // 3. Assert
        $this->assertCount(11, $generatedUrls);
    }
}
