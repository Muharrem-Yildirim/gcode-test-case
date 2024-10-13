<?php

namespace Tests\Unit;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class ImportTodayCommandTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    #[Test]
    public function it_does_it_import_today(): void
    {
        // 1. Arrange
        $date = Carbon::create(2024, 10, 10);

        // 2. Act
        Carbon::setTestNow($date);

        // 3. Assert
        $this->artisan('app:import-today')->assertExitCode(0);
    }
}
