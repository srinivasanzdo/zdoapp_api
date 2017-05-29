<?php

namespace App\Http\Controllers;

use App\Application as Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Get all Application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $application= Application::info()->get();
    	return \Response::json($application);
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
        
        if(Application::create($input))
        {
        	return response()->json(['status' => 1,'message' => "Application Successfully Created."]);
        }
        return response()->json(['status' => 0,'message' => "Application creation error."]);
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
        //
        $application = Application::findOrFail($id);
        
        $input = $request->all();
        
        if($application->fill($input)->save()){
         	return response()->json(['status' => 1,'message' => "Application Successfully Updated."]);
        }
        return response()->json(['status' => 0,'message' => "Application updation error."]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $application = Application::info()->findOrFail($id);
        return \Response::json($application);
    }

    /**
     * Get all Pending Application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPendingApplication()
    {
        $application= Application::info()->pending()->get();
    	return \Response::json($application);
    }

    /**
     * Get all Amend Application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAmendApplication()
    {
        $application= Application::info()->amend()->get();
    	return \Response::json($application);
    }
    /**
     * Get all Draft Application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDraftApplication()
    {
        $application= Application::info()->draft()->get();
    	return \Response::json($application);
    }

    /**
     * Get all Approved Application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getApprovedApplication()
    {
        $application= Application::info()->approved()->get();
    	return \Response::json($application);
    }

    /**
     * Get all Rejected Application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getRejectedApplication()
    {
        $application= Application::info()->rejected()->where('user_id',$id)->get();
    	return \Response::json($application);
    }

    /**
     * Get User Pending Application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserPendingApplication($id)
    {
        $application= Application::info()->pending()->where('user_id',$id)->get();
    	return \Response::json($application);
    }

    /**
     * Get User Amend Application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserAmendApplication($id)
    {
        $application= Application::info()->amend()->where('user_id',$id)->get();
    	return \Response::json($application);
    }
    /**
     * Get User Draft Application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserDraftApplication($id)
    {
        $application= Application::info()->draft()->where('user_id',$id)->get();
    	return \Response::json($application);
    }

    /**
     * Get User Approved Application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserApprovedApplication($id)
    {
        $application= Application::info()->approved()->where('user_id',$id)->get();
    	return \Response::json($application);
    }

    /**
     * Get User Rejected Application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserRejectedApplication($id)
    {
        $application= Application::info()->rejected()->where('user_id',$id)->get();
    	return \Response::json($application);
    }
}
