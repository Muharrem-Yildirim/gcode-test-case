<?php

namespace App\Console\Commands;

use App\Services\TCMBImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ImportTodayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-today';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports TCMB data for today';

    /**
     * Execute the console command.
     */
    public function handle(TCMBImporter $tcmbImporter)
    {
        $this->info('Importing TCMB data for today');

        $importedCount = $tcmbImporter->setDateRange(Carbon::now(), null)->fetch()->store();

        $this->info('Done, imported ' . $importedCount . ' currencies.');

        return $importedCount === 0 ? \Symfony\Component\Console\Command\Command::FAILURE :
            \Symfony\Component\Console\Command\Command::SUCCESS;
    }
}
