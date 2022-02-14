<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ConfigurationAlert;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ConfigurationAlertController extends Controller
{
    public function index()
    {
        $configs = ConfigurationAlert::all();
        $clients = Client::all()->sortBy('name')->pluck('name', 'id');
        return view('client.configuration_alerts.index', compact('configs', 'clients'));
    }

    public function store(Request $request)
    {
        $configAlert = ConfigurationAlert::firstOrNew([
            'command' => $request->configuration_alert,
            'clients_id' => $request->clients_id
        ]);

        $configAlert->name = config('options.configuration_alert')[$request->configuration_alert];
        $configAlert->command = $request->configuration_alert;
        $configAlert->periodicity = $request->periodicity;
        $configAlert->clients_id = $request->clients_id;
        $configAlert->created_users_id = \Auth::id();

        if ($configAlert->save()) {
            Alert::success('Alerta', 'Alerta creada exitosamente');
        }
        return redirect()->back();
    }
}
