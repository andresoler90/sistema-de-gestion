<?php

use App\Models\ConfigurableWord;
use App\Models\ConfigurableWordClient;
use Illuminate\Support\Facades\DB;

function truncate($table)
{
    DB::statement('SET FOREIGN_KEY_CHECKS =0;');
    DB::table($table)->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS =1;');
}

if (!function_exists('setting_client')) {
    function setting_client($slug = null)
    {
        $field = \App\Models\SettingField::where('slug', $slug)->first();
        if ($field) {
            $user = auth()->user();
            $setting = \App\Models\SettingClient::where('setting_field_id', $field->id)->where('client_id', $user->clients_id)->active()->first();

            if ($setting)
                return true;
            else
                return false;
        } else {
            return false;
        }
    }
}

function orderColor($data, $colum, $type){
    if(isset($data)){
        if($data->orderBy == $colum && $data->order == $type){
            return 'primary';
        }
    }
    return 'secondary';
}

function words($word){

    $configurable_word = ConfigurableWord::where('name', $word)->first();

    if(isset($configurable_word)){
        if(Auth::user()->roles_id ==5){
            $client_id = Auth::user()->clients_id;
            $words_client = ConfigurableWordClient::select('name')->where('configurable_words_id', $configurable_word->id)
            ->where('clients_id',$client_id)->first();

            if(isset($words_client)){
                return $words_client->name;
            }
        }
        return $configurable_word->label;
    }
    return '';
}
