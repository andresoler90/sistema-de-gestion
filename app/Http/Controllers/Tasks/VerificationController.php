<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Libs\GeneralMethods;
use App\Http\Controllers\Libs\ProcessFlow;
use App\Mail\GeneralNotification;
use App\Models\RegisterEvent;
use Illuminate\Http\Request;
use App\Models\RegisterTask;
use App\Models\ClientDocument;
use App\Models\ClientTask;
use App\Models\Register;
use App\Models\Tracking;
use App\Models\Verification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;


class VerificationController extends Controller
{
    public function basicDetail(RegisterTask $register_task)
    {
        $category = 5;
        $method = $this->getDocuments($register_task, $category);
        $register = $method[0];
        $register_event = $method[1];
        $my_registers = $method[2];
        $other_registers = $method[3];
        $client_documents = $method[4];

        return view('analyst.verification.detail', compact('register_task', 'register', 'register_event', 'client_documents', 'my_registers','other_registers'));
    }

    public function experienceDetail(RegisterTask $register_task)
    {
        $category = 6;
        $method = $this->getDocuments($register_task, $category);
        $register = $method[0];
        $register_event = $method[1];
        $my_registers = $method[2];
        $other_registers = $method[3];
        $client_documents = $method[4];

        return view('analyst.verification.detail', compact('register_task', 'register', 'register_event', 'client_documents', 'my_registers','other_registers'));
    }

    public function financialDetail(RegisterTask $register_task)
    {
        $category = 7;
        $method = $this->getDocuments($register_task, $category);
        $register = $method[0];
        $register_event = $method[1];
        $my_registers = $method[2];
        $other_registers = $method[3];
        $client_documents = $method[4];

        return view('analyst.verification.detail', compact('register_task', 'register', 'register_event', 'client_documents', 'my_registers','other_registers'));
    }

    public function documentDetail(RegisterTask $register_task)
    {

        $category = 8;
        $method = $this->getDocuments($register_task, $category);
        $register = $method[0];
        $register_event = $method[1];
        $my_registers = $method[2];
        $other_registers = $method[3];
        $client_documents = $method[4];

        return view('analyst.verification.detail', compact('register_task', 'register', 'register_event', 'client_documents', 'my_registers','other_registers'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'registers_id' => 'required',
            'register_tasks_id' => 'required',
            'client_documents_id' => 'required',
            'register_event_id' => 'required',
        ]);

        // return $request;

        $document_satisfy = isset($request->satisfy) ? $request->satisfy : [];

        DB::beginTransaction();
        try {

            foreach ($request->client_documents_id as $key => $value) {
                $verification = new Verification();
                $verification->registers_id = $request->registers_id;
                $verification->register_tasks_id = $request->register_tasks_id;
                $verification->client_documents_id = $value;
                $verification->satisfy = in_array($value, $document_satisfy) ? 'S' : 'N';
                $verification->outcome = $request->outcome[$key];
                $verification->created_users_id = Auth::id();
                $verification->save();
            }

            $register = Register::find($request->registers_id);
            $generalMethods = new GeneralMethods($register);
            //Cerrar el evento del registro
            $generalMethods->closeEvent($request->register_event_id);
            //Cerrar la tarea
            $generalMethods->closeTask($request->register_tasks_id);
            $this->checkVerification($register);

            DB::commit();

            Alert::success(__('Verificación'), __('Se ha registrado la información'));
            return redirect()->route('analyst.tasks.index');

        } catch (\Exception $e) {
            DB::rollBack();
            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                'Problema en la etapa de verificación',
                "Solicitud ".$register->code. ", error $e"
            ));
            Alert::warning(__('Verificación'), __('Ha surgido un error, por favor intente nuevamente'))->persistent('Close');
            return redirect()->back();
        }
    }


    public function getDocuments(RegisterTask $register_task, $category)
    {

        $register = $register_task->register;
        $country_provider = $register->countries_id;
        $country_client = $register->client_country_id;
        $provider_type = ($country_provider == $country_client) ? 'N' : 'I';

        $register_event = RegisterEvent::where('register_tasks_id', $register_task->id)->first();

        $registers_available = Register::select('id')->where([
            ['identification_number', $register->identification_number],
            ['id', '<>', $register->id]
        ])->get();

        $client_task = ClientTask::find($register_task->client_tasks_id);

        $all_registers= RegisterTask::select('register_tasks.*')
        ->join('client_tasks', 'client_tasks.id', 'register_tasks.client_tasks_id')
        ->join('register_events', 'register_events.register_tasks_id', 'register_tasks.id')
        ->whereIn('register_tasks.registers_id', $registers_available)
        ->where([
            ['status', 'A'],
            ['client_tasks.stage_tasks_id', $client_task->stage_tasks_id],
            ['register_events.finished_at', null],
            ['register_events.management', 'PAR'],
        ]);

        $my_registers = $all_registers->where('analyst_users_id', Auth::id())->get();
        $other_registers = $all_registers->where('analyst_users_id','<>' ,Auth::id())->get();


        $client_documents = ClientDocument::where([
            ['clients_id', $register->client->id],
            ['provider_type', $provider_type],
            ['register_type', $register->register_type],
            ['stage_tasks_id', $category],
        ])->with('document')
        ->get();

        return array($register, $register_event, $my_registers,$other_registers, $client_documents);
    }

    /**
     * @param $register
     * Verificacion de tareas realizadas, cuando se encuentren completas todas las tareas
     * Si hay una verificacion fallida avanza a Subsanacion
     * Si todas las verificaciones estan completas avanza a Calidad
     * @return bool
     */
    public function checkVerification($register): bool
    {
        $processFlow = new ProcessFlow($register);
        $generalMethods = new GeneralMethods($register);
        $totalDocuments = $processFlow->totalDocuments();
        $documentsAll = array_sum($totalDocuments->{5});// total documentos de verificación
        $verifications = $register->verifications()->count();

        if ($documentsAll == $verifications) {
            // Se valida las verificaciones fallidas
            $failed = $register->verifications()->notVerified();
            //Si hay una verificacion fallida avanza a Subsanación
            if ($failed->count()) {
                foreach ($failed->cursor() as $data) {
                    $name = str_replace('ve_', 'su_', $data->register_event->stage_task->name);
                    //Se deberá crear las tareas de Subsanación correspondiente a la tarea que presento inconvenientes en la etapa de verificación
                    $sub_tasks[$name] = $name;
                    $analyst_users[$name] = $data->register_task->analyst_users_id;
                }

                Mail::to($register->email_contact)->send(new GeneralNotification(
                    'Observaciones etapa de Verificación',
                    $register->verifications,
                    'mails.verification.notification'
                ));
                if (count(Mail::failures()) == 0) {
                    // Se crea nueva tarea y evento de envio de correo, se cierra inmediatamente modo informativo
                    $generalMethods->createTasksAndEvents(['ve_task_5'], false, 2);
                    //El tiquete automáticamente debe pasar a gestión en el “Proveedor” Se debe pasar automáticamente a la etapa “Subsanación”
                    $generalMethods->createTasksAndEvents($sub_tasks, false, 1, $management = 'PRO',$analyst_users);
                }
            } else {
                //Si todas fueron exitosas se debe: El tiquete debe pasar a gestión en el “Par” Se debe pasar automáticamente a la etapa “Calidad”
                $generalMethods->createTasksAndEvents(['ca_task_1']);
            }
            return true;

        } else
            return false;
    }

}
