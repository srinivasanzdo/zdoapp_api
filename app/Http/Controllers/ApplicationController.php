<?php

namespace App\Http\Controllers;

use App\Application as Application;
use App\Image as Image;
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
        $photo = $input["photo"];
        unset($input["photo"]);

        //return $input;
        if($data = Application::create($input))
        {
            $image_flag = 0;
            foreach ($photo as $key => $value) {
                
                $mrx["application_id"]=$data->id;
                $mrx["type"]=$value["type"];
                $mrx["path"]=$value["path"];

                if(!Image::create($mrx)){
                    $image_flag = 1;
                }

            }
            if($image_flag){
                return response()->json(['status' => 0,'message' => "Application Image save error."], 500);
            }
            return response()->json(['status' => 1,'message' => "Application Successfully Created."]);
        }
        return response()->json(['status' => 0,'message' => "Application creation error."], 500);
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
        return response()->json(['status' => 0,'message' => "Application updation error."], 500);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $application = Application::info()->findOrFail($id)->toArray();
        $images = Application::findOrFail($id)->images;
        $applicationImages = array();
        foreach ($images as $image) {
            $eachImages['type'] = $image['type'];
            $eachImages['path'] = $image['path'];
            $applicationImages['image'][] = $eachImages;
        }
        $application = array_merge($application, $applicationImages);
        return \Response::json($application);
    }

    /**
     * Get all Application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAllApplication()
    {
        $application= Application::info()->get();
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
        $application= Application::info()->rejected()->get();
    	return \Response::json($application);
    }

    /**
     * Get all Resubmit Application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getResubmitApplication()
    {
        $application= Application::info()->resubmit()->get();
    	return \Response::json($application);
    }

    /**
     * Get User all Application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserAllApplication($id)
    {
        $application= Application::info()->where('user_id',$id)->get();
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

    /**
     * Get User Received Application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserReceivedApplication($id)
    {
        $application= Application::info()->received()->where('user_id',$id)->get();
    	return \Response::json($application);
    }

    /**
     * Get Group by count
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getApplicationCount()
    {

        //$user_info = Usermeta::groupBy('browser')->select('browser', DB::raw('count(*) as total'))->get();
        $application= Application::groupBy('status_id')->select('status_id', \DB::raw('count(*) as total'))->get();
    	return \Response::json($application);
    }

     /**
     * Get User Group by count
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserApplicationCount($id)
    {

        //$user_info = Usermeta::groupBy('browser')->select('browser', DB::raw('count(*) as total'))->get();
        $application= Application::groupBy('status_id')->select('status_id', \DB::raw('count(*) as total'))->where('user_id',$id)->get();
    	return \Response::json($application);
    }


}
