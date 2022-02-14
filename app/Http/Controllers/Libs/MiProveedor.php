<?php

namespace App\Http\Controllers\Libs;

use App\Http\Controllers\Controller;
use App\Models\MiProveedor\Act10Relationscl1;
use App\Models\MiProveedor\Act11Relationsact;
use App\Models\MiProveedor\Act6Relcl1masters;
use App\Models\MiProveedor\Cl1Clients;
use App\Models\MiProveedor\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MiProveedor extends Controller
{

    /**
     * Retorna los datos de un proveedor en la bd de MiProveedor
     * @param $identification
     * @param $check_digit
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getProvider($identification, $check_digit = null)
    {

        if ($check_digit!=null) {
            $provider = Provider::with('contact')
                ->where([
                    ['pv1_identification', $identification],
                    ['pv1_dv', $check_digit]
                ])
                ->first();
        } else {
            $provider = Provider::with('contact')
                ->where('pv1_identification', $identification)->first();
        }

        if ($provider) {
            return $provider;
        } else {
            return null;
        }
    }

    public function getGroupClient(): ?array
    {
        $mp_client = Auth::user()->client->mp_client;
        $mp_clients_ids = null;
        if ($mp_client) {
            if ($mp_client->cl5_id) {
                $mp_clients_ids = Cl1Clients::where('cl5_id', $mp_client->cl5_id)->Active()->pluck('cl1_id')->toArray();
            } else {
                $mp_clients_ids = Cl1Clients::where('cl1_id', $mp_client->cl1_id)->Active()->pluck('cl1_id')->toArray();
            }
        }
        return $mp_clients_ids;

    }

    public function getMasterActivity($act1_id = null)
    {
        return Act6Relcl1masters::select('act1.*')->whereIn('cl1_id', $this->getGroupClient())
            ->active()
            ->FilterByMaster($act1_id)
            ->AddAct1Master()
            ->pluck('act1_name', 'act1.act1_id')
            ->toArray();
    }

    public function getTypeActivity($act1_id = null)
    {
        return Act10Relationscl1::whereIn('cl1_id', $this->getGroupClient())
            ->active()
            ->AddAct11Relations()
            ->AddAct10Type()
            ->FilterByMaster($act1_id)
            ->pluck('act10_name', 'act10_type.act10_id')
            ->toArray();
    }

    public function getCategoryActivity($act1_id = null, $act10_id = null): array
    {
        return Act11Relationsact::active()
            ->FilterByMaster($act1_id)
            ->FilterByType($act10_id)
            ->AddAct11Category()
            ->pluck('act11_name', 'act11_category.act11_id')
            ->toArray();
    }

    public function getGroupActivity($act1_id = null, $act10_id = null, $act11_id = null): array
    {
        return Act11Relationsact::active()
            ->FilterByMaster($act1_id)
            ->FilterByType($act10_id)
            ->FilterByCategory($act11_id)
            ->AddAct3Group()
            ->pluck('act3_name', 'act3_grouplists.act3_id')
            ->toArray();
    }

    public function getActivity($act1_id = null, $act10_id = null, $act11_id = null, $act3_id = null): array
    {
        return Act11Relationsact::active()
            ->FilterByMaster($act1_id)
            ->FilterByType($act10_id)
            ->FilterByCategory($act11_id)
            ->FilterByGroup($act3_id)
            ->AddAct2Activity()
            ->pluck('act2_name', 'act2_activities.act2_id')
            ->toArray();
    }


}
