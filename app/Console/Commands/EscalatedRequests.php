<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeneralNotification;
use App\Models\RegisterEvent;
use DateTime;
use App\Http\Controllers\Libs\Festivos;
use App\Models\Register;
use App\Models\RegisterTask;
use App\Models\User;

class EscalatedRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'EscalatedRequests:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cierra las solicitudes que han sido escaladas y no se han gestionado en el tiempo establecido por el cliente';

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

            $registers = Register::select('registers.*','clients.days_waiting','register_events.created_at as escalated_at','register_events.id as event_id')
            ->addClient()
            ->join('register_events','register_events.registers_id','registers.id')
            ->where([
                ['registers.states_id', 3],
                ['register_events.management', 'CLI'],
                ['register_events.finished_at', null],
            ])
            ->get();


            $year = new DateTime();
            $dias_festivos = new Festivos();
            $dias_festivos->getFestivos($year->format('Y'));

            foreach ($registers as $register) {

                $days = $register->days_waiting;
                $dias_laborales = 0;
                $day =1;

                while ($dias_laborales != $days){
                    $date = new DateTime(date('Y-m-d', strtotime($register->escalated_at . ' +'.$day.' day')));

                    if($date->format('Y') != $year->format('Y')){
                        $dias_festivos->getFestivos($date->format('Y'));
                    }
                    if( $date->format('D') != 'Sat' && $date->format('D') != 'Sun' &&
                        !$dias_festivos->esFestivo($date->format('d'),$date->format('m'))
                    ){
                        $dias_laborales++;
                    }
                    $day++;
                }

                $day = $day-1;
                $date = date('Y-m-d', strtotime($register->escalated_at . ' +'.$day.' day')); //Limite para la gestión de la solicitud


                if($date <= date('Y-m-d')){ // Si la fecha limite expiró (Es menor al día actual) o es igual al día actual se cancela la solicitud

                    // Se cierra el evento
                    $register_event = RegisterEvent::find($register->event_id);
                    $register_event->finished_at = now();
                    $register_event->save();

                    // Se cierran todas las tareas relacionadas [Para gestión documental solo es una pero para subsanación pueden ser varias]
                    $register_tasks = RegisterTask::where([['registers_id',$register->id],['status','A']])->get();

                    foreach ($register_tasks as $register_task) {
                        $register_task_new = RegisterTask::find($register_task->id);
                        $register_task_new->end_date = now();
                        $register_task_new->status = 'C';
                        $register_task_new->save();
                    }

                    //Cambiar estado de la solicitud a Cancelado
                    $register_other = Register::find($register->id);
                    $register_other->states_id = 4;
                    $register_other->save();

                    //Crear nuevo evento
                    $register_event_new = new RegisterEvent();
                    $register_event_new->registers_id = $register->id;
                    $register_event_new->stage_tasks_id = $register_event->stage_tasks_id;
                    $register_event_new->states_id = $register_other->states_id; //[states_id]
                    $register_event_new->register_tasks_id = $register_event->register_tasks_id;
                    $register_event_new->management = 'PAR';
                    $register_event_new->description = 'Se cancela la solicitud, el plazo limite para gestionar la solicitud por parte del cliente expiró';
                    $register_event_new->created_users_id = $register_event->created_users_id;
                    $register_event_new->finished_at = now();
                    $register_event_new->save();

                    $user = User::find($register->requesting_users_id);
                    // Se notifica al cliente y al proveedor
                    Mail::to($user->email)
                    ->bcc($register->email_contact)->send(new GeneralNotification(
                        'Solicitud cancelada',
                        "La solicitud $register->code ha sido cancelada, el plazo limite para gestionar la solicitud por parte del cliente expiró",
                    ));
                }
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                'Error en la ejecución del comando EscalatedRequests',
                $exception->getMessage()
            ));
        }
    }
}
