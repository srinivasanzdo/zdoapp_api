<?php

namespace App\Http\Controllers;

use App\User as User;
use Illuminate\Http\Request;
use JWTAuth;
use Auth;
use Mail;

class UserController extends Controller
{
    /**
     * test api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function test(){
        //return "welcome...";
        $user= User::all();
        return response()->json(['status' => 1,'message' => "successfully added.",'data'=>$user]);
    }

    /**
     * Get all user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user= User::info()->get();
    	return \Response::json($user);
    }

    /**
     * Get all Agent user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAllAgent()
    {
        $user= User::info ()->agent()->get();
    	return \Response::json($user);
    }

    /**
     * Login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
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
        
        if(User::create($input))
        {
        	return response()->json(['status' => 1,'message' => "Agent Successfully Created."]);
        }
        return response()->json(['status' => 0,'message' => "Agent creation error."],500);
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
        $user = User::findOrFail($id);
        
        $input = $request->all();
        
        if($user->fill($input)->save()){
         	return response()->json(['status' => 1,'message' => "Profile Successfully Updated."]);
        }
        return response()->json(['status' => 0,'message' => "Profile updation error."], 500);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::info()->findOrFail($id);
        return \Response::json($user);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function currentUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
        
    }

    public function changePassword(Request $request, $id){
        //$user = User::info()->findOrFail($id);
        if (Auth::check())
        {
            $user = User::find(Auth::user()->id);
            return $user;
        }
          
    }

    public function checkmail(){

        $content = "Hi,welcome user!";

        $data = [
            'content' => $content
        ];

        Mail:: raw('mail-template', function($message){
            $message->from('sivakvaij@gmail.com');
            $message->to('mrxzdo@gmail.com');
            $message->subject('Test Mail');
        });

    }

    public function forgotPassword(Request $request){
        $user = User::where('email', '=' ,$request['email'])->first();
        if($user){
            $randomNum=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 11);
            $user->password = $randomNum;
            $user->save();
            $content = "Your new password is ". $randomNum ;
            Mail:: raw($content, function($message)  use ($request){
                $message->from('sivakavij@gmail.com', 'ZDO');
                $message->to($request['email']);
                $message->subject('Reset Password');
            });
            if (Mail::failures()) {
                return response()->json(['status' => 1,'message' => "Mail not working contact your Admin."]);
            }
            return response()->json(['status' => 1,'message' => "Password reset completed . Check your mail.."]);
        }else{
           return response()->json(['status' => 0,'message' => "User does not exist."]); 
        }
    }
}
