<?php

namespace App\Http\Controllers;

use App\Administrator;
use Illuminate\Http\Request;
use Auth;

class AdministratorController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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


    public function registerAdmin(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',
        ]);

        $data = $request->only(['name','email', 'password']);
        $data['password'] = bcrypt($data['password']);

        $admin = Administrator::create($data);

        return response()->json([
            'user' => $admin,
            'token' => $admin->createToken('bigStore')->accessToken,
            'message' => $admin ? "Administrator created" : "Error creating administrator"
        ]);

    }

    //login f(x) for an admin
    public function loginAdmin(Request $request) 
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $credentials = [
            'email' => $email,
            'password' => $password
        ];
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // $admin = Auth::guard('admin')->user();

        if(Auth::guard('admins')->attempt($credentials)) {
            $status = 200;
            $response = [
                'user' => Auth::guard('admins')->user(),
                'token' => Auth::guard('admins')->user()->createToken('bigStore')->accessToken,
                'message' =>  Auth::guard('admins')->user() ? 'Admin logged in' : 'Unable to login'
            ];
            return response()->json($response, $status);
        }
        else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // if(auth()->guard('api-admin')->attempt($credentials)) {
        //     $status = 200;
        //     $response = [
        //         'user' => auth()->guard('api-admin')->user(),
        //         'token' => auth()->guard('api-admin')->user()->createToken('bigStore')->accessToken,
        //         'message' =>  auth()->guard('api-admin')->user() ? 'Admin logged in' : 'Unable to login'
        //     ];
        //     return response()->json($response, $status);
        // }
        // else {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // } 
        // return response()->json(['error' => 'Unauthenticated'], 401);
        
        // $loginData = $request->validate([
        //     'email' => 'email|required',
        //     'password' => 'required|min:6'
        // ]);

        // if (!auth()->attempt($loginData)) {
        //     return response(['message' => 'Invalid Credentials']);
        // }

        // $accessToken = auth()->user()->createToken('bigStore')->accessToken;

        // return response(['user' => auth()->user(), 'token' => $accessToken]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function show(Administrator $administrator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function edit(Administrator $administrator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Administrator $administrator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Administrator $administrator)
    {
        //
    }
}
