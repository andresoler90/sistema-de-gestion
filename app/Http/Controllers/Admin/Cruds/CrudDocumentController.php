<?php

namespace App\Http\Controllers\Admin\Cruds;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;



class CrudDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::paginate(10);
        return view('admin.cruds.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cruds.documents.create');
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
            'name' => 'required'
        ]);

        $document = new Document();
        $document->fill($request->all());
        $document->created_users_id = Auth::id();

        if ($document->save()) {
            Alert::success(__('Documento'), __('Se ha registrado la información'));
            return redirect()->route('documents.edit', $document->id);
        }
        Alert::warning(__('Documento'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        return view('admin.cruds.documents.edit', ['data' => $document]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        $document->fill($request->all());
        $document->created_users_id = Auth::id();

        if ($document->save()) {
            Alert::success(__('Documento'), __('Se ha registrado la información'));
            return redirect()->route('documents.edit', $document->id);
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
    public function destroy(Document $document)
    {
        if ($document->delete()) {
            Alert::success(__('Documento'), __('Se ha eliminado el registro'));
            return redirect()->route('documents.index');
        }
        Alert::warning(__('Documento'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }
}
