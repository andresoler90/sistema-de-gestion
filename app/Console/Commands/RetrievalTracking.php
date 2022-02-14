<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeneralNotification;
use App\Models\RegisterEvent;
use App\Models\Tracking;
use DateTime;
use App\Http\Controllers\Libs\Festivos;
use App\Models\StageTask;

class RetrievalTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RetrievalTracking:update {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza la fecha y el estado de las tareas de seguimiento de la etapa de subsanación y crea una nueva tarea de subsanación de documentos';

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

            if($this->argument('id') == null){
                $register_events = RegisterEvent::select('register_events.*','clients.interval_sub as interval')
                ->join('registers','registers.id','register_events.registers_id')
                ->join('clients','clients.id','registers.clients_id')
                ->where([
                    ['register_events.management','PRO'],
                    ['register_events.finished_at',null],
                ])
                ->whereIn('register_events.stage_tasks_id',[10,11,12,13]) // tareas de subsanación
                ->get();

                $year = new DateTime();
                $dias_festivos = new Festivos();
                $dias_festivos->getFestivos($year->format('Y'));

                foreach ($register_events as $register_event) {

                    $days = $register_event->interval;
                    $dias_laborales = 0;
                    $day =1;

                    while ($dias_laborales != $days){
                        $date = new DateTime(date('Y-m-d', strtotime($register_event->created_at . ' +'.$day.' day')));

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
                    $date = date('Y-m-d', strtotime($register_event->created_at . ' +'.$day.' day'));

                    if($date <= date('Y-m-d')){

                        $register_event_old = RegisterEvent::find($register_event->id);
                        $register_event_old->finished_at = now();
                        $register_event_old->save();

                        $register_event_new = new RegisterEvent();
                        $register_event_new->registers_id = $register_event_old->registers_id;
                        $register_event_new->stage_tasks_id = $register_event_old->stage_tasks_id;
                        $register_event_new->states_id = $register_event_old->states_id; //[states_id]
                        $register_event_new->register_tasks_id = $register_event_old->register_tasks_id;
                        $register_event_new->management = 'PAR';
                        $tracking = Tracking::where([['registers_id',$register_event_old->registers_id],['stage_tasks_id', $register_event_old->stage_tasks_id]])->get();
                        $stage_tasks = StageTask::find($register_event_old->stage_tasks_id);
                        if(count($tracking)==0){
                            $register_event_new->description = $stage_tasks->label;
                        }else{
                            $register_event_new->description = "$stage_tasks->label, Seguimiento ".(count($tracking)+1);
                        }
                        $register_event_new->created_users_id = $register_event_old->created_users_id;
                        $register_event_new->save();
                    }
                }
            }else{

                $register_event_old = RegisterEvent::find($this->argument('id'));
                $register_event_old->finished_at = now();
                $register_event_old->save();

                $register_event_new = new RegisterEvent();
                $register_event_new->registers_id = $register_event_old->registers_id;
                $register_event_new->stage_tasks_id = $register_event_old->stage_tasks_id;
                $register_event_new->states_id = $register_event_old->states_id; //[states_id]
                $register_event_new->register_tasks_id = $register_event_old->register_tasks_id;
                $register_event_new->management = 'PAR';
                $tracking = Tracking::where([['registers_id',$register_event_old->registers_id],['stage_tasks_id', $register_event_old->stage_tasks_id]])->get();
                $stage_tasks = StageTask::find($register_event_old->stage_tasks_id);
                if(count($tracking)==0){
                    $register_event_new->description = $stage_tasks->label;
                }else{
                    $register_event_new->description = "$stage_tasks->label, Seguimiento ".(count($tracking)+1);
                }
                $register_event_new->created_users_id = $register_event_old->created_users_id;
                $register_event_new->save();
            }


            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                'Error en la ejecución del comando RetrievalTracking',
                $exception->getMessage()
            ));
        }
    }
}
