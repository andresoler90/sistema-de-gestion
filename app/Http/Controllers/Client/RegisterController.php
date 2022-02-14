<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Libs\MiProveedor;
use App\Http\Controllers\Libs\ProcessFlow;
use App\Http\Controllers\Tasks\AnalysisManagementController;
use App\Http\Controllers\Tasks\CommercialManagementController;
use App\Mail\GeneralNotification;
use App\Models\RegisterAdditionalField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MiProveedor\Provider;
use App\Models\User;
use App\Models\Client;
use App\Models\ClientDocument;
use App\Models\ClientTask;
use App\Models\PriceList;
use App\Models\Country;
use App\Models\Register;
use App\Models\RegisterEvent;
use App\Models\RegisterTask;
use App\Models\StageTask;
use App\Models\Tracking;

class RegisterController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->roles_id == 5){
            $registers = Register::where('clients_id', Auth::user()->client->id)->paginate(10);
            $tasks = $this->verificationCreateRegister(Auth::user()->client->id);
        }else{
            $registers = Register::where('created_users_id', Auth::id())->paginate(10);
            $tasks = true;
        }
        return view('client.register.index', compact('registers','tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::pluck('name', 'id');
        $clients = Client::pluck('name', 'id');
        return view('client.register.create', compact('countries', 'clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {

        $rule_check_digit = '';

        if( isset($request->countries_id) && $request->countries_id == 43 &&
            isset($request->identification_type) && $request->identification_type == 'NIT'
        ){
            $rule_check_digit = 'required|numeric|min:0|max:9';
        }

        $validated = $request->validate([
            'register_type' => 'required',
            'countries_id' => 'required',
            'client_country_id' => 'required',
            'identification_type' => 'required',
            'identification_number' => 'required',
            'check_digit' => $rule_check_digit,
            'business_name' => 'required',
            'telephone_contact' => 'required',
            'name_contact' => 'required',
            'email_contact' => 'required|email',
        ]);

        DB::beginTransaction();
        try {

            $client_id = ($request->clients_id) ?? Auth::user()->client->id;
            $client = Client::find($client_id);
            $tasks = $this->verificationCreateRegister($client_id);

            if($client->mp_clients_id == null){
                Alert::warning(__('Solicitud'), __('No hay un cliente asociado de Mi Proveedor'))->persistent('Close');
                return redirect()->back();
            }

            if($tasks == false){
                Alert::warning(__('Solicitud'), __('El cliente debe tener asociado al menos una tarea visible por etapa'))->persistent('Close');
                return redirect()->back();
            }

            $validation = Register::where('identification_number', $request->identification_number)
                ->whereIn('states_id', [1, 3, 5]) // 1 : Abierto, 3 : Escalado, 5 : Reabierto, [states_id]
                ->where('clients_id', $client_id)->get();

            if (count($validation) == 0) {

                if ($request->countries_id == 43) {
                    $provider = Provider::where('pv1_identification', $request->identification_number)->first();
                    if ($provider && $provider->pv1_dv != $request->check_digit) {
                        Alert::warning(__('Solicitud'), __('Los datos del proveedor son incorrectos'))->persistent('Close');
                        return redirect()->back();
                    }
                }

                $register = new Register();
                $register->fill($request->all());
                $register->states_id = 1; // Abierto
                $register->created_users_id = Auth::id();

                if (!$request->clients_id) {
                    $register->requesting_users_id = Auth::id();
                    $register->clients_id = Auth::user()->client->id;
                }

                $register->code = $this->code($client->acronym);
                $register->check_digit = $request->countries_id == 43 ? $request->check_digit : null;
                $provider_type = ($request->countries_id == $request->client_country_id)? 'N':'I';

                $price_list = PriceList::where([
                    ['clients_id', $client_id],
                    ['countries_id', $request->client_country_id],
                    ['register_type', $request->register_type],
                    ['provider_type', $provider_type ]
                ])
                ->groupBy('register_assumed_by')
                ->get();

                if (count($price_list) > 1) {
                    $register->register_assumed_by = $request->register_assumed_by;
                } else{
                    if(count($price_list) == 1){
                        $register->register_assumed_by = $price_list[0]->register_assumed_by;
                    }else{
                        Alert::warning(__('Solicitud'), __('No hay responsable de pago para esta combinación en la lista de precios'))->persistent('Close');
                        return redirect()->back();
                    }
                }

                $ProcessFlow = new ProcessFlow($register);
                $verification = $ProcessFlow->totalDocuments()->{5};

                if ($register->register_type == 'L') {//Basico
                    if (
                        !isset($verification['ve_task_1']) &&
                        !isset($verification['ve_task_4'])
                    ){
                        // dd('Liviano/Basico No hay tareas');
                        Alert::warning(__('Solicitud'), __('Ha surgido un error, tareas de verificación insuficientes para el tipo de registro (Liviano)'))->persistent('Close');
                        return redirect()->back();
                    }
                    else if (
                        (isset($verification['ve_task_1']) && $verification['ve_task_1'] == 0) ||
                        (isset($verification['ve_task_4']) && $verification['ve_task_4'] == 0)
                    ) {
                        // dd('Liviano/Basico No hay documentos');
                        $provider = $provider_type =='N'? 'Nacional':'Internacional';
                        Alert::html(
                            __('Solicitud'),
                            __("Ha surgido un error, documentación insuficiente para: <br> El tipo de registro (Liviano) <br> El tipo de proveedor ($provider)"),
                            'warning'
                        )->persistent('Close');
                        return redirect()->back();
                    }
                } elseif ($register->register_type == 'I') {
                    if (
                        !isset($verification['ve_task_1']) &&
                        !isset($verification['ve_task_2']) &&
                        !isset($verification['ve_task_3']) &&
                        !isset($verification['ve_task_4'])
                    ){
                        // dd('Integral No hay tareas');
                        Alert::warning(__('Solicitud'), __('Ha surgido un error, tareas de verificación insuficientes para el tipo de registro (Integral)'))->persistent('Close');
                        return redirect()->back();
                    }
                    else if (
                        (isset($verification['ve_task_1']) && $verification['ve_task_1'] == 0) ||
                        (isset($verification['ve_task_2']) && $verification['ve_task_2'] == 0) ||
                        (isset($verification['ve_task_3']) && $verification['ve_task_3'] == 0) ||
                        (isset($verification['ve_task_4']) && $verification['ve_task_4'] == 0)
                    ) {
                        // dd('Integral No hay documentos');
                        $provider = $provider_type =='N'? 'Nacional':'Internacional';
                        Alert::html(
                            __('Solicitud'),
                            __("Ha surgido un error, documentación insuficiente para: <br> El tipo de registro (Integral) <br> El tipo de proveedor ($provider)"),
                            'warning'
                        )->persistent('Close');
                        return redirect()->back();
                    }
                }

                if ($register->save()) {
                    // Creación de la solicitud
                    $register_task = new RegisterTask();
                    $client_task = ClientTask::where([['clients_id', $client->id], ['stage_tasks_id', 1]])->first();
                    $register_task->client_tasks_id = $client_task->id;
                    $register_task->registers_id = $register->id;
                    $register_task->start_date = now();
                    $register_task->end_date = now();
                    $register_task->status = 'C';
                    $register_task->created_users_id = $register->created_users_id;
                    $register_task->save();

                    $register_event = new RegisterEvent();
                    $register_event->registers_id = $register->id;
                    $register_event->states_id = $register->states_id; //[states_id]
                    $register_event->register_tasks_id = $register_task->id;
                    $register_event->management = 'CLI';
                    $register_event->description = 'Creación de la solicitud';
                    $register_event->created_users_id = $register->created_users_id;
                    $register_event->finished_at = now();
                    $register_event->save();

                    if ($request->get('act1_id') !== null){
                        try {
                            $additional_fields = new RegisterAdditionalField();
                            $additional_fields->register_id = $register->id;
                            $additional_fields->act1_id = $request->get('act1_id');
                            $additional_fields->act2_id = $request->get('act2_id');
                            $additional_fields->act3_id = $request->get('act3_id');
                            $additional_fields->act10_id = $request->get('act10_id');
                            $additional_fields->act11_id = $request->get('act11_id');
                            $additional_fields->code_activity = $request->get('code_activity');
                            $additional_fields->code_intern = $request->get('code_intern');
                            $additional_fields->experience_verify = ($request->get('experience_verify') == 'on') ? 1 : 0;
                            $additional_fields->save();
                        } catch (\Exception $exception) {
                            DB::rollBack();
                            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                                'Error al registrar las actividades',
                                "REQUEST: ".implode(';',$request->all())."\nEXCEPTION: ".$exception->getMessage()
                            ));
                            Alert::warning(__('Alerta'),__('Se ha presentado un inconveniente al registrar las actividades'))->persistent('Close');
                            return redirect()->back();
                        }
                    }

                    try{
                        // Flujo de analisis de un registro
                        $analysis = new AnalysisManagementController($register);
                        if ($analysis->nextStage == 3){
                            // Etapa comercial -> Se envia a SalesForce
                            new CommercialManagementController($register);
                        }
                        else if ($analysis->nextStage == 4){

                            $analyst = $ProcessFlow->analyticsAvaible($analysis->nextStage,$register->identification_number);
                            if($analyst == null){
                                DB::rollBack();
                                Alert::warning(__('Alerta'),__('No hay analistas para gestionar la solicitud'))->persistent('Close');
                                return redirect()->back();
                            }
                        }
                        DB::commit();
                    } catch (\Exception $exception) {
                        DB::rollBack();
                        Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                            'Error en salesforce',
                            "REQUEST: ".implode(';',$request->all())."\nEXCEPTION: ".$exception->getMessage()
                        ));
                        Alert::warning(__('Alerta'),__('Se ha presentado un inconveniente en salesforce'))->persistent('Close');
                        return redirect()->back();
                    }


                    Alert::success(__('Solicitud')." $register->code", __('Se ha registrado la información'))->persistent('Close');
                    return redirect()->route('registers.show', $register->id);
                }
                Alert::warning(__('Solicitud'), __('Ha surgido un error, por favor intente nuevamente'))->persistent('Close');
                return redirect()->back();
            } else {
                Alert::warning(__('Solicitud'), __('Existe una solicitud en proceso para este proveedor'))->persistent('Close');
                return redirect()->back();
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            Mail::to(env('MAIL_TO_USER'))->send(new GeneralNotification(
                'Error al crear el registro',
                "REQUEST: ".implode(';',$request->all())."\nEXCEPTION: ".$exception->getMessage()
            ));
            Alert::warning(__('Alerta'),__('Se ha presentado un inconveniente al crear el registro'))->persistent('Close');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Register $register)
    {
        $trackings_gd = Tracking::where([['registers_id', $register->id], ['stage_tasks_id', 4]])->orderBy('created_at', 'desc')->get();
        $trackings_sub = Tracking::where('registers_id',$register->id)
            ->whereIn('stage_tasks_id',[10,11,12,13])
            ->orderBy('stage_tasks_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.register.show', ['data' => $register, 'trackings_gd'=>$trackings_gd, 'trackings_sub'=>$trackings_sub]);
    }


    public function getPriceList(Request $request)
    {

        if(Auth::user()->roles_id ==5)
            $client = User::find(Auth::id())->client->id;
        else
            $client = $request->client_id;

        $price_list = PriceList::where([
            ['clients_id', $client],
            ['countries_id', $request->country_client_id],
            ['register_type', $request->register_type],
            ['provider_type', $request->provider_type]
        ])
        ->groupBy('register_assumed_by')
        ->get();

        return response()->json(['price_list' => $price_list]);
    }

    public function code($acronym)
    {

        $registers = Register::withTrashed()->where('code', 'like', $acronym . '%')->get();
        $number = (count($registers) + 1);
        $zero = ($number < 10) ? '00' : (($number < 100) ? '0' : '');
        $code = $acronym . $zero . $number;

        return $code;
    }

    public function getProvider(Request $request)
    {
        $miProveedor = new MiProveedor();
        $provider = $miProveedor->getProvider($request->identification, ($request->check_digit)??null);

        if ($provider) {
            return response()->json(['status' => 200, 'provider' => $provider]);
        } else {
            return response()->json(['status' => 500, 'title' => __('Solicitud'), 'msg' => __('Proveedor nuevo en MiProveedor')]);
        }
    }

    public function verificationCreateRegister($client_id){
        // Verificar que por cada etapa tenga al menos una tarea visible

        $stage_task = StageTask::select('stages_id')->where('visible','S')->where('stages_id','!=',3)->groupBy('stages_id')->get();

        $client_task = ClientTask::select('stage_tasks.stages_id')
        ->join('stage_tasks','stage_tasks.id','client_tasks.stage_tasks_id')
        ->where([
            ['clients_id',$client_id],
            ['stage_tasks.visible','S']
        ])
        ->whereIn('stage_tasks.stages_id',$stage_task)
        ->groupBy('stage_tasks.stages_id')
        ->get();

        $tasks = (count($stage_task) == count($client_task));

        return $tasks;
    }
}
