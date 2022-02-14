<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ConfigurableWord;
use App\Models\ConfigurableWordClient;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class ConfigurableWordClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $words = ConfigurableWordClient::paginate(10);
    //     return view('admin.cruds.configurable_words.index', compact('words'));
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($clients_id)
    {
        $words_client = ConfigurableWordClient::select('configurable_words_id')->where('clients_id',$clients_id)->get();
        $words = ConfigurableWord::whereNotIn('id',$words_client)->pluck('label','id')->toArray();
        return view('admin.cruds.clients.configurable_words.create',compact('clients_id','words'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'configurable_words_id' => 'required',
            'clients_id' => 'required',
        ]);

        $word = new ConfigurableWordClient();
        $word->fill($request->all());
        $word->created_users_id = Auth::id();

        if ($word->save()) {
            Alert::success(__('Palabra'), __('Se ha registrado la información'));
            return redirect()->route('configurable.words.clients.edit', $word->id);
        }
        Alert::warning(__('Palabra'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ConfigurableWordClient $client)
    {
        $words_client = ConfigurableWordClient::select('configurable_words_id')
        ->where([
            ['clients_id',$client->clients_id],
            ['configurable_words_id','<>',$client->configurable_words_id],
        ])
        ->get();
        $words = ConfigurableWord::whereNotIn('id',$words_client)->pluck('label','id')->toArray();

        $data = $client;
        return view('admin.cruds.clients.configurable_words.edit',compact('data','words'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ConfigurableWordClient $client)
    {
        $validated = $request->validate([
            'name' => 'required',
            'configurable_words_id' => 'required',
            'clients_id' => 'required',
        ]);

        $client->fill($request->all());
        $client->created_users_id = Auth::id();

        if ($client->save()) {
            Alert::success(__('Palabra'), __('Se ha registrado la información'));
            return redirect()->route('configurable.words.clients.edit', $client->id);
        }
        Alert::warning(__('Palabra'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ConfigurableWordClient $client)
    {
        if ($client->delete()) {
            Alert::success(__('Palabra'), __('Se ha eliminado el registro'));
            return redirect()->back();
        }
        Alert::warning(__('Palabra'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }
}
