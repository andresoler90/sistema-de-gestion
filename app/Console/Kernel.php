<?php

namespace App\Console;

use App\Models\ConfigurationAlert;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\DocumentationTracking::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Genera las tareas de la gestion documental
        $schedule->command('DocumentationTracking:update')->daily();

        //Genera las tareas para la subsanacion
        $schedule->command('RetrievalTracking:update')->daily();

        //Consulta los estados de las oportunidades creadas en salesforce
        $schedule->command('salesforce:find')->everyTenMinutes();

        //Alertas configurables
        $alerts = ConfigurationAlert::all();
        foreach ($alerts as $alert) {

            $command = false;

            switch ($alert->command) {
                case 'alerts:client_resume':
                    $command = "alerts:client_resume " . $alert->clients_id;
                    break;
            }

            if ($command) {

                switch ($alert->periodicity) {

                    case 'every_minute':
                        $schedule->command($command)->everyMinute();
                        break;

                    case 'daily':
                        $schedule->command($command)->daily();
                        break;

                    case 'weekly':
                        $schedule->command($command)->weekly();
                        break;

                    case 'monthly':
                        $schedule->command($command)->monthly();
                        break;
                }
            }
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
