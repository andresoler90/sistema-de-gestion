<?php

namespace App\Http\Controllers\Admin\Cruds;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\SettingField;
use App\Models\SettingClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CrudSettingsController extends Controller
{

    public function index()
    {
        $settingFields = SettingField::all();
        $settingClients = SettingClient::all();
        $fields = SettingField::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $clients = Client::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        return view('admin.cruds.settings.index', compact('settingFields', 'settingClients', 'clients', 'fields'));

    }

    public function fieldSave(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:setting_fields',
        ]);

        $settingFields = new SettingField();
        $settingFields->fill($request->all());
        if ($settingFields->save()) {
            Alert::success('Campos', 'Se ha registrado correctamente');
        } else
            Alert::error('Campos', 'Se ha generado un error en la creación');

        return redirect()->back();

    }

    public function fieldUpdate(Request $request, SettingField $field): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        if (!isset($request->default_value)) {
            $request['default_value'] = 0;
        }

        $field->fill($request->all());
        if ($field->save()) {
            Alert::success('Campos', 'Se ha actualizado correctamente');
        } else
            Alert::error('Campos', 'Se ha generado un error en la creación');

        return redirect()->back();

    }

    public function fieldClientSave(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'setting_field_id' => 'required',
            'client_id' => 'required',
        ]);
        $validate = SettingClient::where('setting_field_id', $request->setting_field_id)->where('client_id', $request->client_id)->first();

        if (!$validate) {
            $setting_field = SettingField::where('id', $request->setting_field_id)->first();
            if ($setting_field) {
                $setting_client = new SettingClient();
                $setting_client->fill($request->all());
                $setting_client->created_user_id = \Auth::id();
                $setting_client->status = $setting_field->default_value;
                if ($setting_client->save()) {
                    Alert::success('Campo cliente', 'Se ha registrado correctamente');
                }
            }
        } else {
            Alert::error('Campo cliente', 'Ya existe este campo asociado al cliente');
        }
        return redirect()->back();

    }

    public function fieldClientUpdate(Request $request): \Illuminate\Http\RedirectResponse
    {
        foreach ($request->hidden_id as $key => $value) {
            $setting_client[$key] = SettingClient::find($value);
            $setting_client[$key]->status = $request->get('hidden_status_' . $value);
            $setting_client[$key]->updated_user_id = \Auth::id();
            $setting_client[$key]->save();
        }
        Alert::success('Campo cliente', 'Se ha actualizado correctamente');
        return redirect()->back();
    }
}
