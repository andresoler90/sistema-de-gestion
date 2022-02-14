<?php


namespace App\Http\Controllers\Libs;

use App\Mail\GeneralNotification;
use App\Models\ClientTask;
use App\Models\Register;
use App\Models\RegisterEvent;
use App\Models\RegisterTask;
use App\Models\Stage;
use App\Models\StageTask;
use App\Models\User;
use DateTime,DatePeriod, DateInterval;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Object_;

class ProcessFlow
{
    /**
     * @var Register
     */
    public $register;

    public function __construct(Register $register = null)
    {
        $this->register = $register;
    }

    /**
     * Retorna el nombre de la etapa en la que se encuentra la solicitud
     */
    public function stage()
    {
        $register_event = RegisterEvent::where('registers_id',$this->register->id)
        ->orderBy('id','DESC')->first();
        $stage_task = StageTask::find($register_event->stage_tasks_id);
        $stage = Stage::find($stage_task->stages_id);
        return $stage;
    }

    /**
     * Retorna todas las tareas generadas hasta el momento
     */
    public function tasks()
    {
        $register_event = RegisterEvent::select('stage_tasks_id')->where('registers_id',$this->register->id)->get();
        $stage_task = StageTask::whereIn('id',$register_event)->get();

        return $stage_task;
    }

    /**
     * Retorna la tarea actual
     */
    public function currentTask(){
        $register_event = RegisterEvent::select('stage_tasks_id')
        ->where('registers_id',$this->register->id)
        ->orderBy('id','DESC')->first();

        $stage_task = StageTask::find($register_event->stage_tasks_id);

        return $stage_task;
    }

    /**
     * Retorna la tarea siguiente a la actual
     */
    public function nextTask($name = null)
    {
        if ($name) {
            $current = StageTask::where('name', $name)->first()->id;
            $operator = '=';
        }else{
            $current = $this->currentTask()->id;
            $operator = '>';
        }

        return ClientTask::where([['clients_id', $this->register->clients_id], ['stage_tasks_id', $operator, $current]])
            ->orderBy('stage_tasks_id')->first();

    }

    /**
     * Retorna la tarea previa a la actual
     */
    public function previousTask()
    {
        $current = $this->currentTask()->id;

        $client_tasks = ClientTask::where([['clients_id',$this->register->clients_id],['stage_tasks_id','<',$current]])
        ->orderBy('stage_tasks_id','DESC')->first();

        if($client_tasks){
            $stage_task = StageTask::find($client_tasks->stage_tasks_id);
            return $stage_task;

        }else{
            return null;
        }
    }

    /**
     * Retorna el tiempo transcurrido desde que se realizo la solicitud
     */
    public function totalTime(RegisterEvent $registerEvent = null)
    {

        if ($registerEvent){
            $register_events = RegisterEvent::select('id','created_at','finished_at')
                ->where('id',$registerEvent->id)->get();
        }else{
            $register_events = RegisterEvent::select('id','created_at','finished_at')
                ->where('registers_id', $this->register->id)->get();
        }


        $minutos_totales = 0;
        $hora_almuerzo = 12;
        $horas_no_laborales = [17,18,19,20,21,22,23,0,1,2,3,4,5,6,$hora_almuerzo];

        $dias_festivos = new Festivos();
        $dias_festivos->getFestivos();

        foreach ($register_events as $register_event) {

            $fechaInicial = new DateTime($register_event->created_at);
            $fechaFinal = new DateTime($register_event->finished_at);

            $interval = DateInterval::createFromDateString('1 minutes');
            $period = new DatePeriod($fechaInicial, $interval, $fechaFinal);

            foreach ($period as $dt) {
                if(
                $dt->format('D') != 'Sat' && $dt->format('D') != 'Sun' && // Sin fin de semana
                !in_array($dt->format('H'), $horas_no_laborales) && // Sin horas fuera del horario laboral
                !$dias_festivos->esFestivo($dt->format('d'),$dt->format('m')) // sin dias festivos
                ){
                    // echo $dt->format("Y-m-d H:i:s D"). '<br>';
                    $minutos_totales++;
                }
            }
        }

        $minutos = $minutos_totales % 60;
        $horas_totales = ($minutos_totales - $minutos) / 60;
        $horas = $horas_totales % 24;
        $dias = ($horas_totales - $horas) / 24;

        return (object) ['days'=> $dias,'hours' => $horas, 'minutes' => $minutos];

    }

    /**
     * Retorna el tiempo inicial y final de una etapa
     */
    public function stageTime($stage_id)
    {
        $tasks = StageTask::select('stage_tasks.id')->join('client_tasks','client_tasks.stage_tasks_id','stage_tasks.id')
        ->where([['stages_id',$stage_id],['clients_id',$this->register->clients_id]])->get();


        $register_event = RegisterEvent::select('created_at', 'finished_at')
        ->where('registers_id',$this->register->id)
        ->whereIn('stage_tasks_id',$tasks)->get();

        $start = strtotime($register_event[0]->created_at->format("Y-m-d H:i:s"));

        if(count($tasks) > count($register_event) || $register_event[0]->finished_at == null){
            return (object) ['start'=> date("Y-m-d H:i:s", $start),'end' => null];
        }else{
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $register_event[0]->finished_at);
            $end = strtotime($date->format("Y-m-d H:i:s"));
        }

        foreach ($register_event as $value) {
            if($value->finished_at == null){
                return (object) ['start'=> date("Y-m-d H:i:s", $start),'end' => null];
            }
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $value->finished_at);
            $other = strtotime($date->format("Y-m-d H:i:s"));

            if($end < $other){
                $end = $other;
            }
        }

        return (object) ['start'=> date("Y-m-d H:i:s", $start),'end' => date("Y-m-d H:i:s", $end)];
    }

    /**
     * Retorna el tiempo de cada una de las etapas generadas en esta solicitud
     */
    public function stagesTime()
    {
        $register_event = RegisterEvent::select('stage_tasks_id')
        ->where('registers_id',$this->register->id)
        ->orderBy('stage_tasks_id')->get();

        $stage_task = StageTask::select('stages_id')->whereIn('id',$register_event)->get();
        $stage = Stage::whereIn('id',$stage_task)->get();

        $stages = [];

        foreach($stage as $value => $s){
            $time = $this->stageTime($s->id);
            $stages[$value] = (object) [
                'id'   => $s->id,
                'name' => $s->name,
                'start'=> $time->start,
                'end'  => $time->end,
            ];
        }
        return $stages;
    }

    /**
     * Retorna el id del analista con menos carga laboral
     */
    public function analyticsAvaible($task, $identification = null)
    {
        // Se busca si existe un tiquete en cualquiera de los estados Abierto, Re-abierto o Escalado al cliente
        // del mismo proveedor que ya haya sido trabajado en la tarea $task

        $register = Register::select('id')->where('identification_number',$identification)
        ->whereIn('states_id',[1,3,5]) // 1: Abierto, 2:Escalado al cliente, 3:Reabierto
        ->get();

        if(count($register) >0){

            $register_event = RegisterEvent::select('register_tasks_id')
            ->whereIn('registers_id',$register)
            ->where('stage_tasks_id',$task)
            ->get();

            if(count($register_event) >0){

                $register_task = RegisterTask::select('analyst_users_id')
                ->whereIn('id',$register_event)
                ->where('analyst_users_id','<>',null)
                ->first();

                if(isset($register_task) && $register_task != null){
                    return $register_task->analyst_users_id;
                }
            }
        }

        // Se busca los analistas que no se les haya asignado tareas y se escoge el primero
        $register_task = RegisterTask::select('users.id')->rightJoin('users','users.id','register_tasks.analyst_users_id')
        ->join('analyst_tasks','analyst_tasks.analyst_id','users.id')
        ->where([
            ['users.roles_id', 2],
            ['analyst_tasks.stage_tasks_id', $task],
            ['users.deleted_at', null],
            ['analyst_tasks.deleted_at', null],
            ['register_tasks.analyst_users_id', null]
        ])->first();

        if(!isset($register_task)){

            $register_tasks = RegisterTask::select('analyst_users_id','status',DB::raw('count(*) as tasks'))
                ->join('users','users.id','register_tasks.analyst_users_id')
                ->join('analyst_tasks','analyst_tasks.analyst_id','users.id')
                ->where([
                    ['analyst_tasks.stage_tasks_id', $task],
                    ['analyst_tasks.deleted_at', null],
                    ['users.deleted_at', null],
                ])
                ->groupBy('analyst_users_id','status')->get();

            $id = null;
            foreach($register_tasks as $analista1){
                if($analista1->status == 'C'){
                    $id = $analista1->analyst_users_id;
                    foreach($register_tasks as $analista2){
                        if($analista2->status == 'A' && $id == $analista2->analyst_users_id)
                            $id = null;
                    }
                }
            }

            if($id == null && count($register_tasks) >0){

                $menor = $register_tasks[0]->tasks;
                $id = $register_tasks[0]->analyst_users_id;

                foreach($register_tasks as $analista){
                    if($analista->status == 'A'){
                        if($analista->tasks < $menor){
                            $id = $analista->analyst_users_id;
                            $menor = $analista->tasks;
                        }
                    }
                }
            }
        }else{
            $id = $register_task->id;
        }

        return $id;
    }

    public function managementTime($management = 'PAR', $stage_tasks_id = 'ALL'){

        if($stage_tasks_id == 'ALL'){
            $register_events = RegisterEvent::select('id','created_at','finished_at')
            ->where([
                ['registers_id', $this->register->id],
                ['management',$management]
            ])->get();
        }else{
            $register_events = RegisterEvent::select('id','created_at','finished_at')
            ->where([
                ['registers_id', $this->register->id],
                ['management',$management],
                ['stage_tasks_id',$stage_tasks_id],
            ])->get();
        }

        $minutos_totales = 0;
        $hora_almuerzo = 12;
        $horas_no_laborales = [17,18,19,20,21,22,23,0,1,2,3,4,5,6,$hora_almuerzo];

        $dias_festivos = new Festivos();
        $dias_festivos->getFestivos();

        foreach ($register_events as $register_event) {

            $fechaInicial = new DateTime($register_event->created_at);
            $fechaFinal = new DateTime($register_event->finished_at);

            $interval = DateInterval::createFromDateString('1 minutes');
            $period = new DatePeriod($fechaInicial, $interval, $fechaFinal);

            foreach ($period as $dt) {
                if(
                $dt->format('D') != 'Sat' && $dt->format('D') != 'Sun' && // Sin fines de semana
                !in_array($dt->format('H'), $horas_no_laborales) && // Sin horas fuera del horario laboral
                !$dias_festivos->esFestivo($dt->format('d'),$dt->format('m')) // Sin dias festivos
                ){
                    $minutos_totales++;
                }
            }
        }

        $minutos = $minutos_totales % 60;
        $horas_totales = ($minutos_totales - $minutos) / 60;
        $horas = $horas_totales % 24;
        $dias = ($horas_totales - $horas) / 24;

        return (object) [
            'total_minutes'=> $minutos_totales,
            'total_hours'=> $minutos_totales/60,
            'days'=> $dias,
            'hours' => $horas,
            'minutes' => $minutos
        ];
    }

    /**
     * @return bool
     * @comment anular solicitud de un registro y envio de correo
     */
    public function cancelRegister(): bool
    {
        $this->register->states_id = 6; // anulado
        if ($this->register->save()) {
            $register_task = $this->register->tasks->where('status', 'A')->last();
            if ($register_task) { // se cierra tarea
                $register_task->status = 'C';
                $register_task->end_date = now();
                if ($register_task->save()) {
                    // Se cierra el evento
                    $events = $register_task->events->last();
                    $events->finished_at = now();
                    $events->save();

                    // Se crea un nuevo evento
                    $register_event = new RegisterEvent();
                    $register_event->registers_id = $this->register->id;
                    $register_event->stage_tasks_id = $events->stage_tasks_id;
                    $register_event->states_id = $this->register->states_id; //[states_id]
                    $register_event->register_tasks_id = $events->register_tasks_id;
                    $register_event->management = 'PAR';
                    $register_event->description = 'Se anula la solicitud';
                    $register_event->created_users_id = $events->created_users_id;
                    $register_event->finished_at = now();
                    $register_event->save();
                }
            }
            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                'Solicitud Anulada',
                "Se ha anulado la solicitud para el proveedor: " . $this->register->identification_number
            ));

            return true;

        } else
            return false;
    }

    /**
     * Cantidad de Documentos obligatorios por evento del registro
     */
    public function totalDocuments(int $stage_id = 5,$documentType = 'ALL'): Object_
    {
        $data = new Object_();
        $registerType = $this->register->register_type; //Tipo de registro L: Liviano / I:Integral
        // stages a los cuales se les aplica filtros adicionales
        if ($registerType == 'L') {
            $stageEnabled =
                [
                    've_task_1',
                    've_task_4'
                ];
        } elseif ($registerType == 'I') {
            $stageEnabled =
                [
                    've_task_1',
                    've_task_2',
                    've_task_3',
                    've_task_4'
                ];
        }

        $client_task = $this->register->client_tasks()
            ->select('client_tasks.*','stage_tasks.*')
            ->join('stage_tasks', 'stage_tasks.id', '=', 'client_tasks.stage_tasks_id')
            ->where('stage_tasks.stages_id', $stage_id)
            ->whereIn('stage_tasks.name', $stageEnabled);

        foreach ($client_task->cursor() as $task){
            $query = $this->register->client_documents()
                ->where('provider_type', $this->typeNationality())
                ->where('register_type', $registerType)
                ->where('stage_tasks_id',$task->stage_tasks_id)
                ->documentType($documentType);

            // $name = str_replace(' ', '_', $task->name);
            $data->{$stage_id}[$task->name] = $query->count();

        }
        return $data;

    }

    /**
     * @return string
     * Tipo de nacionalidad de un registro: Nacional o Internacional
     */
    public function typeNationality(): string
    {
        $country_client = $this->register->client_country_id;
        $country_provider = $this->register->countries_id;

        return ($country_provider == $country_client) ? 'N' : 'I';

    }

    /**
     * @return int
     * Verifica cantidad de tareas abiertas del registro
     */
    public function getTotalOpenTask(): int
    {
        return $this->register->tasks()->where('status','A')->count();
    }

}
