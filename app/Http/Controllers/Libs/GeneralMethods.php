<?php

namespace App\Http\Controllers\Libs;

use App\Http\Controllers\Controller;
//use App\Mail\GeneralNotification;
use App\Models\Register;
use App\Models\RegisterEvent;
use App\Models\RegisterTask;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Mail;

class GeneralMethods extends Controller
{

    private $register;
    private $processFlow;

    /**
     * GeneralMethods constructor.
     * @param Register|null $register Registro del proveedor
     */
    public function __construct(Register $register = null)
    {
        if($register != null){
            $this->register = $register;
            $this->processFlow = new ProcessFlow($register);
        }
    }

    /**
     * @param array $stage_tasks Tareas a guardar
     * @param bool $analystUser Si la tarea requiere un analista asignado
     * @param int $states_id Estado del evento, default 1 = Activo
     * @param array $usersByTask Analistas especificos asignados a la tarea debe contener el indice de $stage_tasks EJ:
     * $analyst_users[ve_task1] = 1 (users_id) @todo para usar la variable $usersByTask se debe enviar la variable $analystUser = false
     * @param string $priority = Nivel de prioridad de la tarea
     */
    public function createTasksAndEvents(array $stage_tasks = array(), bool $analystUser = true, int $states_id = 1, string $management = 'PAR', array $usersByTask = array(), string $priority = 'MED')
    {
        $analyst = true;
        if (count($stage_tasks)) {
            foreach ($stage_tasks as $name) {
                $nextClientTask = $this->processFlow->nextTask($name);
                // save new task and event
                $newTask = new RegisterTask();
                $newTask->client_tasks_id = $nextClientTask->id;
                $newTask->registers_id = $this->register->id;
                $newTask->start_date = now();
                $newTask->created_users_id = $this->register->created_users_id;
                $newTask->priority = $priority;
                if ($analystUser){
                    $newTask->analyst_users_id = $this->processFlow->analyticsAvaible($nextClientTask->stage_tasks_id, $this->register->identification_number);
                    if($analyst){
                        $analyst = $newTask->analyst_users_id == null? false : true;
                    }
                }
                if ($analystUser == false && count($usersByTask))
                    $newTask->analyst_users_id = $usersByTask[$name] ?? null;
                if ($states_id == 2){// cerrado
                    $newTask->end_date = now();
                    $newTask->status = 'C';
                }
                if ($newTask->save()) {
                    $newEvent = new RegisterEvent();
                    $newEvent->registers_id = $this->register->id;
                    $newEvent->stage_tasks_id = $nextClientTask->stage_tasks_id;
                    $newEvent->states_id = $this->register->states_id; //[states_id]
                    $newEvent->register_tasks_id = $newTask->id;
                    $newEvent->description = $nextClientTask->stage_task->label;
                    $newEvent->created_users_id = $this->register->created_users_id;
                    if ($states_id == 2)// cerrado
                        $newEvent->finished_at = now();
                    $newEvent->management = $management;
                    $newEvent->save();
                }
            }
        }

        return $analyst;
    }

    /**
     * @param int $register_tasks_id
     * Metodo general para cerrar tarea
     */
    public function closeTask(int $register_tasks_id)
    {
        $register_tasks = RegisterTask::find($register_tasks_id);
        $register_tasks->end_date = now();
        $register_tasks->status = 'C';
        $register_tasks->save();
    }

    /**
     * @param int $register_event_id
     * Cerrar el evento del registro
     */
    public function closeEvent(int $register_event_id)
    {
        $register_event = RegisterEvent::find($register_event_id);
        $register_event->finished_at = now();
        $register_event->save();
    }

}
