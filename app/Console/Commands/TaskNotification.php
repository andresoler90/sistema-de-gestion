<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeneralNotification;
use App\Models\RegisterEvent;
use DateTime;
use App\Http\Controllers\Libs\Festivos;
use App\Models\Client;
use App\Models\Register;
use App\Models\RegisterTask;
use App\Models\User;

class TaskNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TaskNotification:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Se notifica al analista las tareas que no se han gestionado y se le notifica al coordinador si el analista hizo caso omiso';

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
        DB::beginTransaction();
        try {


            $analysts_notification = RegisterTask::where([['status','A'],['analyst_users_id','<>',null],['analyst_notification','N']])->get();

            foreach ($analysts_notification as $analyst ) {

                $analyst_time = $analyst->register->client->analyst_time;

                $date = date('Y-m-d H:m:s', strtotime($analyst->created_at . ' +'.$analyst_time.' hour'));

                if($date <= date('Y-m-d H:m:s')){

                    //Enviar notificación al analista
                    $user = User::find($analyst->analyst_users_id);
                    $register= Register::find($analyst->registers_id);
                    $stage = $register->stage->name;
                    Mail::to($user->email)->send(new GeneralNotification(
                        'Tarea pendiente de gestión',
                        "Una tarea en la etapa de $stage del registro $register->code esta pendiente, recuerda terminarla a tiempo para que no se marque como una tarea atrasada",
                    ));

                    //Marcamos que la tarea ya fue notificada al analista
                    $analyst->analyst_notification = 'S';
                    $analyst->save();

                    // cuando se esta en desarrollo se pueden enviar maximo 5 correos por segundo
                    // por eso se usa sleep(1); para que espere y luego siga enviando
                    if(env('MAIL_HOST', false) == 'smtp.mailtrap.io'){
                        sleep(1); //use usleep(500000) for half a second or less
                    }

                }
            }

            $coordinators_notification = RegisterTask::where([['status','A'],['analyst_users_id','<>',null],['coordinator_notification','N']])->get();

            foreach ($coordinators_notification as $coordinator ) {

                $coordinator_time = $coordinator->register->client->coordinator_time;

                $date = date('Y-m-d H:m:s', strtotime($coordinator->created_at . ' +'.$coordinator_time.' hour'));

                if($date <= date('Y-m-d H:m:s')){

                    //Enviar notificación a los coordinadores
                    $register= Register::find($coordinator->registers_id);
                    $stage = $register->stage->name;

                    //Marcamos que la tarea ya fue notificada al coordinador
                    $coordinator->coordinator_notification = 'S';

                    //Marcar tarea como atrasada
                    $coordinator->management_status = 'ATR';
                    $coordinator->save();

                    $user_coordinators = User::where('roles_id',3)->get(); // 3 rol de coordinador

                    foreach ($user_coordinators as $$user_coordinator) {

                        Mail::to($user_coordinator->email)->send(new GeneralNotification(
                            'Tarea pendiente de gestión',
                            "Una tarea en la etapa de $stage del registro $register->code esta pendiente de gestión, se marcara como atrasada",
                        ));

                        if(env('MAIL_HOST', false) == 'smtp.mailtrap.io'){
                            sleep(1); //use usleep(500000) for half a second or less
                        }
                    }
                }
            }


            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                'Error en la ejecución del comando TaskNotification',
                $exception->getMessage()
            ));
        }
    }
}
