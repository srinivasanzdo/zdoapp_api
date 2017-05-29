<?php

namespace App\Http\Controllers;

use App\User as User;
use Illuminate\Http\Request;
use JWTAuth;

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
        return response()->json(['status' => 0,'message' => "Agent creation error."]);
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
}
