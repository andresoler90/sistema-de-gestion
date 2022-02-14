<?php

namespace App\Http\Controllers\Admin\Cruds;

use App\Http\Controllers\Libs\SalesForce;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Country;
use App\Models\PriceList;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


class CrudPriceListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $price_lists = PriceList::paginate(10);

        $clients = Client::pluck('name','id');
        return view('admin.cruds.pricelist.index',compact('price_lists','clients'));
    }

    public function search(Request $request)
    {

        $price_lists = PriceList::select('*')
        ->when($request->client, function ($query) use ($request){
            return $query->where('clients_id',$request->client);
        })
        ->when($request->orderBy, function ($query) use ($request){
            if($request->order != 'ASC' && $request->order != 'DESC'){
                $request->order = 'ASC';
            }

            $newOrderBy = '';
            switch ($request->orderBy) {
                case 'client':
                    $newOrderBy = 'clients_id';
                    break;
                default:
                    $newOrderBy = 'clients_id';
                    break;
            }
            return $query->orderBy($newOrderBy,$request->order);

        })
        ->paginate(10);

        $clients = Client::pluck('name','id');
        $data = $request;

        return view('admin.cruds.pricelist.index',compact('price_lists','clients','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::pluck('name','id');
        $clients = Client::pluck('name','id');
        return view('admin.cruds.pricelist.create',compact('countries','clients'));
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
            'countries_id' => 'required',
            'register_assumed_by' => 'required',
            'clients_id' => 'required',
            'register_type' => 'required',
            'currency' => 'required',
        ]);

        $price_list = new PriceList();
        $price_list->fill($request->all());
        if ($price_list->save()) {
            Alert::success(__('Lista de precios'), __('Se ha registrado la información'));
            return redirect()->route('price.list.edit', $price_list->id);
        }
        Alert::warning(__('Lista de precios'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PriceList $pricelist)
    {
        $countries = Country::pluck('name','id');
        $clients = Client::pluck('name','id');
        $data = $pricelist;

        return view('admin.cruds.pricelist.edit',compact('countries','clients','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PriceList $pricelist)
    {
        $validated = $request->validate([
            'countries_id' => 'required',
            'register_assumed_by' => 'required',
            'clients_id' => 'required',
            'register_type' => 'required',
            'currency' => 'required',
        ]);

        $pricelist->fill($request->all());
        if ($pricelist->save()) {
            Alert::success(__('Lista de precios'), __('Se ha actualizado la información'));
            return redirect()->route('price.list.edit', $pricelist->id);
        }

        Alert::warning(__('Lista de precios'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PriceList $pricelist)
    {
        if ($pricelist->delete()) {
            Alert::success(__('Lista de precios'), __('Se ha eliminado el registro'));
            return redirect()->route('price.list.index');
        }
        Alert::warning(__('Lista de precios'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }

    /**
     * @return array
     * Consulta lista de precios Salesforce
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPriceListSalesForce(): array
    {
        $salesforce = new SalesForce();

        return $salesforce->getProductList()->pluck('Name','Id')->toArray();

    }

}
