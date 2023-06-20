<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\shared\Company;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = User::query();
        if ($request->filled('company_id')) {

            $query = $query->where('company_id', $request->company_id);
        }
        if ($request->filled('department_id')) {
            $query = $query->where('department_id', $request->department_id);
        }

        if ($request->filled('active')) {
            $query = $query->where('active', $request->active);
        }
        if ($request->filled('name')) {
            $query = $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('username')) {
            $query = $query->where('username', 'like', '%' . $request->username . '%');
        }
        if ($request->filled('email')) {
            $query = $query->where('email', 'like', '%' . $request->email . '%');
        }

        $users = $query->get();
        $users->load('roles');
        if ($request->filled('type')) {

            $ListCompany = array();
            $companies = $users->pluck('company_id')->flatten();
            $companies->sort();
            $companies = array_unique($companies->toArray());
            $companies = Company::whereIn('id', $companies)->get();
            foreach ($companies as $key => $comp) {
                $ItemCompany = array();
                $ItemCompany['label'] = $comp->id . "-" . $comp->name;
                $ItemCompany['id'] = "c" . $comp->id;
                $ItemCompany['children'] = array();
                foreach ($users as $key => $user) {
                    if ($user->company_id == $comp->id) {
                        $item['label'] =  $user->name . ' (' . $user->username . ')';
                        $item['id'] =  $user->id;
                        array_push($ItemCompany['children'], $item);
                    }
                }

                array_push($ListCompany, $ItemCompany);
            }
            $users = $ListCompany;
        }

        $result = array();
        $result['data'] = $users;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
