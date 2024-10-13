<?php

namespace Tests\Feature;

use App\Console\Commands\ImportTodayCommand;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CurrencyControllerApiTest extends TestCase
{
    #[Test]
    public function it_returns_200(): void
    {
        // 1. Arrange
        $response = $this->getJson(route('currencies.index'));

        // 2. Act

        // 3. Assert
        $response->assertOk();
    }

    #[Test]
    public function it_returns_currencies_correct_format(): void
    {
        // 1. Arrange
        $date = Carbon::create(2024, 10, 10);
        Carbon::setTestNow(testNow: $date);

        // 2. Act
        $this->artisan('app:import-today');
        $response = $this->getJson(route('currencies.index'));

        // 3. Assert
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'cross_order',
                    'code',
                    'unit',
                    'name',
                    'forex' => [
                        'buying',
                        'selling',
                    ],
                    'banknote' => [
                        'buying',
                        'selling',
                    ],
                    'cross_rate' => [
                        'usd',
                        'other',
                    ],
                    'date',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    #[Test]
    public function it_validates_dates(): void
    {
        // 1. Arrange
        $notValidDate  = 'this-is-not-correct-date';
        $validDate = Carbon::now()->subDays(value: 30)->startOfWeek(CarbonInterface::MONDAY);

        // 2. Act
        $responseNotValid = $this->getJson(route('currencies.index', [
            'start_date' => $notValidDate,
            'end_date' => $notValidDate,
        ]));

        $responseValid = $this->getJson(route('currencies.index', [
            'start_date' => $validDate,
            'end_date' => $validDate,
        ]));

        $responseValidWithoutEndDate = $this->getJson(route('currencies.index', [
            'start_date' => $validDate,
            'end_date' => null,
        ]));

        $responseValidWithEndDate = $this->getJson(route('currencies.index', [
            'start_date' => $validDate,
            'end_date' => $validDate->clone()->addDays(2),
        ]));

        // 3. Assert
        $responseNotValid->assertJsonValidationErrorFor('start_date');
        $responseNotValid->assertJsonValidationErrorFor('end_date');

        $responseValid->assertOk();
        $responseValidWithoutEndDate->assertOk();
        $responseValidWithEndDate->assertOk();
    }
}
