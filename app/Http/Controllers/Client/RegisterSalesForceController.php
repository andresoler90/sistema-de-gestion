<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Register;
use App\Models\RegisterContact;
use App\Models\RegisterSalesForce;
use App\Models\RegisterSalesforceActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use RealRashid\SweetAlert\Facades\Alert;

class RegisterSalesForceController extends Controller
{

    public function index()
    {
        $registers = [];
        $contacts = RegisterContact::query();
        $register_salesforce = RegisterSalesForce::query();

        foreach ($contacts->cursor() as $contact) {
            $processFlow = new \App\Http\Controllers\Libs\ProcessFlow($contact->register);
            if ($contact->register != null && $processFlow->currentTask()->stages_id == 3)
                $registers[$contact->register_id] = $contact;
        }
        foreach ($register_salesforce->cursor() as $saleforce) {
            $processFlow = new \App\Http\Controllers\Libs\ProcessFlow($saleforce->register);
            if ($saleforce->register != null && $processFlow->currentTask()->stages_id == 3)
                $registers[$saleforce->register_id] = $saleforce;
        }

        return view('admin.cruds.commercial.index', compact('registers'));
    }

    public function show_contact(Register $register, RegisterContact $contact)
    {
        $dataJson = json_decode($contact->data_json);
        return view('admin.cruds.commercial.contacts.show', compact('register', 'contact', 'dataJson'));
    }

    public function show_activity(Register $register, RegisterSalesforceActivity $activity)
    {
        $dataJson = json_decode($activity->data_json);
        return view('admin.cruds.commercial.activities.show', compact('register', 'activity', 'dataJson'));
    }

    public function register_show(Register $register)
    {
        $contacts = RegisterContact::where('register_id', $register->id)->paginate(15);
        $register_salesforce = RegisterSalesForce::where('register_id', $register->id)->paginate(15);
        $activities = RegisterSalesforceActivity::where('register_id', $register->id)->paginate(15);

        return view('admin.cruds.commercial.show', compact('register', 'register_salesforce', 'contacts','activities'));

    }

    public function execute_salesforce_opportunity(RegisterSalesForce $salesforce): \Illuminate\Http\RedirectResponse
    {
        Artisan::call("salesforce:find opportunity $salesforce->register_id");
        Alert::success('Comercial', 'Se ha ejecutado la consulta correctamente');
        return redirect()->back();
    }

}
