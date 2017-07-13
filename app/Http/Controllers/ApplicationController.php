<?php

namespace App\Http\Controllers;

use App\Application as Application;
use App\Image as Image;
use App\Link as Link;
use Illuminate\Http\Request;
use Mail;

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

    public function sendLinkEmail(Request $request){
        $input = $request->all();
        $full_link = $input["full_link"];
        $tomail = $input["email"];
        unset($input["full_link"]);
        unset($input["email"]);

        $resend_content = '';

        if(array_key_exists("resend_content",$input)){
            $resend_content = $input["resend_content"];
            unset($input["resend_content"]);
        }

        if(Link::create($input)){
            $content ="Remark : " . $resend_content . "\r\n\r\n" . "Your application link  is ". $full_link ;
            Mail:: raw($content, function($message)  use ($tomail){
                $message->from('sivakavij@gmail.com', 'ZDO');
                $message->to($tomail);
                $message->subject('Application Link');
            });
            if( count(Mail::failures()) > 0 ) {
                return response()->json(['status' => 1,'message' => "Mail not working contact your Admin."]);
            }
            return response()->json(['status' => 1,'message' => "Application link send to client."]);
        }
        return response()->json(['status' => 0,'message' => "Application link send error"], 500);
    }

    public function sendLinkWhatsapp(Request $request){
        $input = $request->all();
        
        if(Link::create($input)){
            return response()->json(['status' => 1,'message' => "Application link send to client."]);
        }
        return response()->json(['status' => 0,'message' => "Application link send error"], 500);
    }

    public function linkDetail($li){
        $link = Link::where('link', '=' ,$li)->first();
        if($link){
            return \Response::json($link);
        }
        return \Response::json($link);
    }

    public function updatelink(Request $request){
        $link = Link::where('link', '=' ,$request["link"])->first();
        if($link){
            $link->status = 'close';
             $link->save();
            return response()->json(['status' => 1,'message' => "link update success"]);
        }
        return response()->json(['status' => 0,'message' => "link update failed"]);
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

            $application = Application::findOrFail($data->id);
            $application->application_no = 'ZDO-APP-'.$data->id;
            $application->save();

            return response()->json(['status' => 1,'message' => "Application Successfully Created."]);
        }
        return response()->json(['status' => 0,'message' => "Application creation error."], 500);
    }

    public function applicationFormSubmit(Request $request)
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

            $application = Application::findOrFail($data->id);
            $application->application_no = 'ZDO-APP-'.$data->id;
            $application->save();
            
            return response()->json(['status' => 1,'message' => "Application Successfully Created. Contact your agent"]);
        }
        return response()->json(['status' => 0,'message' => "Application creation error. Contact your agent"], 500);
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

        $applicationDetail = Application::info()->findOrFail($id)->toArray();
        
        $input = $request->all();
        $st = '';
        
        if($input["status_id"]==2){
            $st = 'AMENDED';
        }else if($input["status_id"]==4){
            $st = 'APPROVED';
        }else if($input["status_id"]==5){
            $st = 'REJECTED';
        }else{

        }

        $name = $applicationDetail["name"];
        $appno = $applicationDetail["application_no"];

        $mailcontent = "Dear Agent ," . "\r\n\r\n" . " Your application for " . $name . " & " . $appno . " has been " . $st;
        $toemail = $applicationDetail["user"]["email"];

        if(array_key_exists("photo",$input)){
            $photo = $input["photo"];
                unset($input["photo"]);

                    $image_flag = 0;
                    foreach ($photo as $key => $value) {

                        $img = Image::findOrFail($value["id"]);
                        
                        $mrx["type"]=$value["type"];
                        $mrx["path"]=$value["path"];

                        if($img->fill($mrx)->save()){
                            $image_flag = 1;
                        }

                    }

        }
        
        if($application->fill($input)->save()){

            if($input["status_id"]==2 || $input["status_id"]==4 || $input["status_id"]==5){
                Mail:: raw($mailcontent, function($message)  use ($toemail){
                    $message->from('sivakavij@gmail.com', 'ZDO');
                    $message->to($toemail);
                    $message->subject('Application Status');
                });
            }

         	return response()->json(['status' => 1,'message' => "Application Successfully Updated."]);
        }
        return response()->json(['status' => 0,'message' => "Application updation error."], 500);
        
    }

    public function updateStatus(Request $request, $id)
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editapplicationFormSubmit(Request $request, $id)
    {
        
        $application = Application::findOrFail($id);
  
        $input = $request->all();
        
        if(array_key_exists("photo",$input)){
            $photo = $input["photo"];
                unset($input["photo"]);

                    $image_flag = 0;
                    foreach ($photo as $key => $value) {

                        $img = Image::findOrFail($value["id"]);
                        
                        $mrx["type"]=$value["type"];
                        $mrx["path"]=$value["path"];

                        if($img->fill($mrx)->save()){
                            $image_flag = 1;
                        }

                    }

        }
        
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
            $eachImages['id'] = $image['id'];
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
        $application= Application::infoadmin()->get();
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
