<?php

namespace App\Http\Controllers\Admin\Cruds;

use App\Http\Controllers\Controller;
use App\Models\ConfigurableWord;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;



class CrudConfigurableWordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $words = ConfigurableWord::paginate(10);
        return view('admin.cruds.configurable_words.index', compact('words'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cruds.configurable_words.create');
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
            'name' => 'required|unique:configurable_words',
            'label' => 'required'
        ]);

        $word = new ConfigurableWord();
        $word->fill($request->all());
        $word->created_users_id = Auth::id();

        if ($word->save()) {
            Alert::success(__('Palabra'), __('Se ha registrado la información'));
            return redirect()->route('configurable.words.edit', $word->id);
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
    public function edit(ConfigurableWord $word)
    {
        return view('admin.cruds.configurable_words.edit', ['data' => $word]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ConfigurableWord $word)
    {
        $validated = $request->validate([
            // 'name' => 'required|unique:configurable_words,name,'.$word->id,
            'label' => 'required'
        ]);

        $word->label = $request->label;

        if ($word->save()) {
            Alert::success(__('Palabra'), __('Se ha registrado la información'));
            return redirect()->route('configurable.words.edit', $word->id);
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
    public function destroy(ConfigurableWord $word)
    {
        if ($word->delete()) {
            Alert::success(__('Palabra'), __('Se ha eliminado el registro'));
            return redirect()->route('configurable.words.index');
        }
        Alert::warning(__('Palabra'), __('Ha surgido un error, por favor intente nuevamente'));
        return redirect()->back();
    }
}
