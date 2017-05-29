<?php

namespace App\Http\Controllers;

use App\Status as Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Get all Status
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status= Status::all();
    	return \Response::json($status);
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
        
        if(Status::create($input))
        {
        	return response()->json(['status' => 1,'message' => "Status Successfully Created."]);
        }
        return response()->json(['status' => 0,'message' => "Status creation error."]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $status = Status::findOrFail($id);
        return \Response::json($status);
    }
}
