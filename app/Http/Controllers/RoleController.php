<?php

namespace App\Http\Controllers;

use App\Role as Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Get all Role
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role= Role::all();
    	return \Response::json($role);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $input = $request->all();
        
        if(Role::create($input))
        {
        	return response()->json(['status' => 1,'message' => "Role Successfully Created."]);
        }
        return response()->json(['status' => 0,'message' => "Role creation error."], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        return \Response::json($role);
    }
}
