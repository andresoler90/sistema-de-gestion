<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Libs\Festivos;
use App\Http\Controllers\Libs\GeneralMethods;
use App\Http\Controllers\Libs\ProcessFlow;
use App\Http\Controllers\Tasks\DocumentManagementController;

use App\Models\Register;
use App\Models\RegisterEvent;
use App\Models\RegisterTask;
use Illuminate\Http\Request;
use DateTime;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function prueba()
    {

//        $document = new DocumentManagementController();

        // $register = Register::find(40);
//        $verification = $document->verificationTasksNext($register);
        // $process_flow = new ProcessFlow($register);
        // $generalMethods = new GeneralMethods($register);
        // $generalMethods->checkVerification();


        // return $process_flow->tasks();





    }
}
