<?php

namespace App\Http\Controllers\Admin\Cruds;

use App\Http\Controllers\Controller;
use App\Models\ClientDocument;
use App\Models\Document;
use App\Models\Register;
use App\Models\StageTask;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;



class CrudClientDocumentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($clients_id)
    {
        $documents = Document::pluck('name', 'id');
        $stage_tasks = StageTask::whereIn('id',[5,6,7,8])->pluck('label', 'id');

        return view('admin.cruds.clients.documents.create', compact('clients_id','documents','stage_tasks'));
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
            'documents_id' => 'required',
            'register_type' => 'required',
            'provider_type' => 'required',
            'document_type' => 'required',
            'clients_id' => 'required',
        ]);

        $registers = Register::where('clients_id',$request->clients_id)->whereNotIn('states_id',[2,4,6])->get();

        if (count($registers)>0) {
            Alert::warning(__('Cliente'), __('No se puede crear documentos, existen solicitudes pendientes por gestionar'))->persistent('Close');
            return redirect()->back();
        }

        $client_document = new ClientDocument();
        $client_document->fill($request->all());
        $client_document->created_users_id = Auth::id();
        if ($client_document->save()) {
            Alert::success(__('Documento'), __('Se ha registrado la información'))->persistent('Close');
            return redirect()->route('client.document.edit', $client_document->id);
        }
        Alert::warning(__('Documento'), __('Ha surgido un error, por favor intente nuevamente'))->persistent('Close');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientDocument $document)
    {
        $data = $document;
        $documents = Document::pluck('name', 'id');
        $stage_tasks = StageTask::whereIn('id',[5,6,7,8])->pluck('label', 'id');

        return view('admin.cruds.clients.documents.edit', compact('data', 'documents','stage_tasks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientDocument $document)
    {
        $registers = Register::where('clients_id',$document->clients_id)->whereNotIn('states_id',[2,4,6])->get();

        if (count($registers)>0) {
            Alert::warning(__('Cliente'), __('No se puede editar los documentos, existen solicitudes pendientes por gestionar'))->persistent('Close');
            return redirect()->back();
        }

        $validated = $request->validate([
            'documents_id' => 'required',
            'register_type' => 'required',
            'provider_type' => 'required',
            'document_type' => 'required',
        ]);

        $document->fill($request->all());
        if ($document->save()) {
            Alert::success(__('Documento'), __('Se ha actualizado la información'))->persistent('Close');
            return redirect()->route('client.document.edit', $document->id);
        }
        Alert::warning(__('Documento'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientDocument $document)
    {

        $registers = Register::where('clients_id',$document->clients_id)->whereNotIn('states_id',[2,4,6])->get();

        if (count($registers)>0) {
            Alert::warning(__('Cliente'), __('No se puede eliminar los documentos, existen solicitudes pendientes por gestionar'))->persistent('Close');
            return redirect()->back();
        }

        if ($document->delete()) {
            Alert::success(__('Documento'), __('Se ha eliminado el registro'))->persistent('Close');
            return redirect()->back();
        }
        Alert::warning(__('Documento'), __('Ha surgido un error, por favor intente nuevamente'))->persistent('Close');
        return redirect()->back();
    }
}
