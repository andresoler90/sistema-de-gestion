<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientsCountry;
use App\Models\Country;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;


class ClientCountryController extends Controller
{
    public function create($clients_id)
    {
        $client_countries = ClientsCountry::select('countries_id')->where('clients_id',$clients_id)->get();
        $countries = Country::whereNotIn('id',$client_countries)->pluck('name','id')->toArray();

        return view('admin.cruds.clients.countries.create',compact('clients_id','countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'clients_id' => 'required',
            'countries_id' => 'required',
        ]);

        $client_country = new ClientsCountry();
        $client_country->fill($request->all());
        $client_country->created_users_id = Auth::id();
        if ($client_country->save()) {
            Alert::success(__('Países del cliente'), __('Se ha registrado la información'));
            return redirect()->route('clients.edit', $request->clients_id);
        }
        Alert::warning(__('Países del cliente'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }

    public function destroy(ClientsCountry $client_country)
    {
        if ($client_country->delete()) {
            Alert::success(__('Países del cliente'), __('Se ha desasociado el país'));
            return redirect()->back();
        }
        Alert::warning(__('Países del cliente'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }
}
