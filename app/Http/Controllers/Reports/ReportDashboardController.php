<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Register;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportDashboardController extends Controller
{
    public function index()
    {
        $method = $this->getData();
        $scaled_requests = $method[0];
        $all_requests = $method[1];
        $open_requests = $method[2];
        $clients = $method[3];

        return view('reports.dashboard.index',compact('scaled_requests','all_requests','open_requests','clients'));
    }

    public function search(Request $request)
    {
        $method = $this->getData($request);
        $scaled_requests = $method[0];
        $all_requests = $method[1];
        $open_requests = $method[2];
        $clients = $method[3];
        $data = $request;

        return view('reports.dashboard.index',compact('scaled_requests','all_requests','open_requests','clients','data'));
    }

    public function export()
    {
        return 'export';
    }

    public function getData(Request $request = null)
    {
        $is_client = Auth::user()->roles_id == 5;

        $scaled_requests = Register::where('states_id',3)
        ->when($is_client, function ($query){
            return $query->where('registers.clients_id',Auth::user()->client->id);
        })
        ->when($request, function ($query) use ($request){
            if (isset($request->client))
                return $query->where('clients_id',$request->client);
        })->get();

        $open_requests = Register::where('states_id',1)
        ->when($is_client, function ($query){
            return $query->where('registers.clients_id',Auth::user()->client->id);
        })
        ->when($request, function ($query) use ($request){
            if (isset($request->client))
                return $query->where('clients_id',$request->client);
        })->count();

        $users_clients = User::where('roles_id',5)
        ->when($is_client, function ($query){
            return $query->where('clients_id',Auth::user()->client->id);
        })
        ->when($request, function ($query) use ($request){
            if (isset($request->client))
                return $query->where('clients_id',$request->client);
        })
        ->get();

        $clients = Client::all()->pluck('name','id')->toArray();
        $all_requests = [];

        foreach ($users_clients as $client) {
            $registers = Register::where('requesting_users_id',$client->id)->get();
            $abierto = $cerrado = $escalado = $anulado = $cancelado = $total = 0;

            if(count($registers)){
                foreach ($registers as $register) {
                    switch ($register->states_id) {
                        case 1: // Abierto
                            $abierto++;
                            break;
                        case 2: // Cerrado
                            $cerrado++;
                            break;
                        case 3: // Escalado
                            $escalado++;
                            break;
                        case 4: // Cancelado
                            $cancelado++;
                            break;
                        case 6: // Anulado
                            $anulado++;
                            break;
                    }
                }
                $total = $abierto + $cerrado + $escalado + $anulado + $cancelado;
                $all_requests[] = (object)[
                    'name' => $client->name,
                    'abierto' => $abierto,
                    'cerrado' => $cerrado,
                    'escalado' => $escalado,
                    'anulado' => $anulado,
                    'cancelado' => $cancelado,
                    'total' => $total,
                ];
            }
        }

        return [$scaled_requests,$all_requests,$open_requests,$clients];
    }
}
