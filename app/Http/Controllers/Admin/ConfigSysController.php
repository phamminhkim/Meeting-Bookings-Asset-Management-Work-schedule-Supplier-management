<?php

namespace App\Http\Controllers\Admin;

use App\Models\shared\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\BaseController\BaseController;
use App\Models\shared\ConfigSys;

class ConfigSysController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $configsyss = ConfigSys::paginate(15);

        return view('admin.configsys.index')->with('configsyss', $configsyss);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.configsys.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

		//dd($request['id']);
        $configsys =  ConfigSys::create([
            'id' => $request['id'],
			'value' => $request['value'],
        ]);

        $request->session()->flash('success', $configsys->id . ' has been created');
        return redirect()->route('adminconfigsyss.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\shared\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(ConfigSys $configSys)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\shared\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $configsys = ConfigSys::find($id);

        return view('admin.configsys.edit')->with([
            'configsys' => $configsys,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\shared\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request )
    {
        $this->validate($request,
        [
            'id' => 'required|max:255',
            'value' => 'required|max:255',

        ],
        [
            'required' => ':attribute Không được để trống',


        ]);
        $configSys = ConfigSys::find($request->id);
        $configSys->value = $request->value;
        $configSys->id = $request->id;


        if ($configSys->save()) {
            $request->session()->flash('success', $configSys->id . ' has been updated');
        } else {
            $request->session()->flash('error', 'There was an error updating the company');
        }

        return redirect()->route('adminconfigsyss.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\shared\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        try {
             $configSys = ConfigSys::find($id);
           //  dd($id);
            // if($company->delete()){
            //     return redirect()->route('admincompanies.index');
            // }
              if ($configSys->delete()) {
                $request->session()->flash('success', 'Deleted successfully');
            } else {
                $request->session()->flash('error', 'There was an error delete');
            }
        } catch (\Throwable $th) {
            $request->session()->flash('error', 'There was an error delete' );
        }

        return redirect()->route('adminconfigsyss.index');
    }
	 public function search_all(Request $request)
    {
        $searchTerm  = $request->nav_search_input;
        $configsyss = ConfigSys::where('id', 'LIKE', "%{$searchTerm}%")
            ->orWhere('value',  'LIKE', "%{$searchTerm}%")
            ->paginate(15)->appends(request()->except('page'));

        return view('admin.configsys.index')->with(['configsyss' => $configsyss, 'searchTerm' => $searchTerm]);
    }
}
