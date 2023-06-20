<?php

namespace App\Http\Controllers\Admin;

use App\Models\shared\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CompanyRequest;
use App\Http\Controllers\BaseController\BaseController;
class CompanyController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::paginate(15);

        return view('admin.company.index')->with('companies', $companies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         
        return view('admin.company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    { 
		 
		//dd($request['id']);
        $company =  Company::create([
            'id' => $request['id'],
			'name' => $request['name'],
			'active' => $request['active']
           
        ]);
      
        $request->session()->flash('success', $company->name . ' has been created');
        return redirect()->route('admincompanies.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\shared\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\shared\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
             
        
        return view('admin.company.edit')->with([           
            'company' => $company,            
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\shared\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $this->validate($request,
        [
            'id' => 'required|max:4|max:4',
            'name' => 'required|max:50',
			'active' => 'required',
        ],
        [
            'required' => ':attribute Không được để trống',
            'min' => ':attribute Không được nhỏ hơn :min',
            'max' => ':attribute Không được lớn hơn :max',
          
        ]);
        $company->name = $request->name;
        $company->active = $request->active;


        if ($company->save()) {
            $request->session()->flash('success', $company->name . ' has been updated');
        } else {
            $request->session()->flash('error', 'There was an error updating the company');
        }

        return redirect()->route('admincompanies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\shared\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Company $company)
    {      
        try {
            // if($company->delete()){
            //     return redirect()->route('admincompanies.index');
            // }
              if ($company->delete()) {
                $request->session()->flash('success', 'Deleted successfully');
            } else {
                $request->session()->flash('error', 'There was an error delete the user');
            }
        } catch (\Throwable $th) {
            $request->session()->flash('error', 'There was an error delete the user' );
        }
       
        return redirect()->route('admincompanies.index');
    }
	 public function search_all(Request $request)
    {
        $searchTerm  = $request->nav_search_input;
        $companies = Company::where('name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('id',  'LIKE', "%{$searchTerm}%")        
            ->paginate(15)->appends(request()->except('page'));

        return view('admin.company.index')->with(['companies' => $companies, 'searchTerm' => $searchTerm]);
    }
}
