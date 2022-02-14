<?php

namespace App\Console\Commands\Alerts;

use App\Mail\GeneralNotification;
use App\Mail\ResumeClientMail;
use App\Models\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ClientResumeAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:client_resume {client_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = Client::find($this->argument('client_id'));
        if ($client) {
            Mail::to('santiago.suarez@parservicios.com')->send(new ResumeClientMail($client));
        } else {
            $this->error('El cliente no existe!');
        }
        return 0;
    }
}
