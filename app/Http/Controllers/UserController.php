<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Validator;
use DB;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Auth\Events\PasswordReset;


class UserController extends Controller
{
    use SendsPasswordResetEmails, ResetsPasswords {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
        ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
    }

    /**
     * Send password reset link. 
     */
    public function sendPasswordResetLink(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json([
            'message' => 'Password reset email sent.',
            'data' => $response
        ]);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json([
                'message' => 'Email could not be sent to this email address.',
            ]
        );
    }

    /**
     * Handle reset password 
     */
    public function callResetPassword(Request $request)
    {
        return $this->reset($request);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        // $user->password = Hash::make($password);
        $user->password = bcrypt($password);
        $user->save();
        event(new PasswordReset($user));
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Password reset successfully.']);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Failed, Invalid Token.']);
    }

    //all users with their orders
    public function index() {
        return response()->json(User::with(['orders'])->get());
        // return response()->json(User::with(['orders','product'])->get(), 200);

    }

    // public function paginatedUsers() {
    //     // return response()->json(User::all(), 200);
    //     $data = User::latest()->paginate(4);
    //     return response()->json($data, 200);
    // }
    public function allUsers() {
        // return response()->json(User::all(), 200);
        $data = User::latest()->paginate(4);
        return response()->json($data, 200);
    }

    //revoke the user's token 
    public function logout() {
        $user = Auth::user()->token();
        $user->revoke();
        
        return response()->json([
            'status' => (bool) Auth::user(),
            // 'data' => Auth::user(),
            'message' => Auth::user() ? 'You are logged out' : 'Failed to revoke token'
        ]);  
    }
    //authenticates a user and generates an access token for that user
    public function login(Request $request) {
        // $status = 401;
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
        if(Auth::attempt($credentials)) {
            $status = 200;
            $response = [
                'user' => Auth::user(),
                'token' => Auth::user()->createToken('bigStore')->accessToken,
                'message' =>  Auth::user() ? 'User logged in' : 'Unable to sign-in'
            ];
        } 
        // else if (empty($email) && empty($password)) {
        //     return response()->json(['error' => 'Bad content'], 400);
        // }
        else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json($response, $status);
    }

    //creates a user account, authenticates it and generates an access token for it
    public function registerBuyer(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:50',
            'phoneNumber' => 'required|min:10|starts_with:07',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',
        ],
        [
           'phoneNumber.starts_with' => ' :attribute number must begin with 07. e.g 0788001122'
        ]);

        $data = $request->only(['name', 'email', 'password', 'is_admin', 'phoneNumber', 'subAccID']);
        $data['password'] = bcrypt($data['password']);
        $data['is_admin'] = false;

        $user = User::create($data); 

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('bigStore')->accessToken,
            'message' => $user ? "Buyer's account created" : "Error creating buyer account"
        ]);
    }

    // Registering a user as a vendor
    public function registerVendor(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users',
            'phoneNumber' => 'required|min:10',
            'password' => 'required|min:6',
            'subAccID' => 'required',
            'c_password' => 'required|same:password',
        ]);
         
        $data = $request->only(['name', 'email', 'password', 'is_admin', 'phoneNumber', 'subAccID']);
        $data['password'] = bcrypt($data['password']);
        $data['is_admin'] = true;

        $user = User::create($data);
        // $user->is_admin = true; //the problem is here

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('bigStore')->accessToken,
            'message' => $user ? 'Vendor created' : 'Error creating vendor'
        ]);
    }

    public function updateUser(Request $request, User $user) {
        $this->validate($request, [
            'name' => 'required',
            'phoneNumber' => 'required|min:10'
        ]);

        $status = $user->update(
            $request->only(['name', 'email', 'password', 'phoneNumber'])
        );

        return response()->json([
            'data' => $user,
            'status' => $status,
            'message' => $status ? 'User successfully updated!' : 'User failed to update, try again'
        ]);
    }

    public function show(User $user) {
        return response()->json($user);
    }

    public function showOrders(User $user) {
        return response()->json($user->orders()->with(['product'])->get(), 200); 
    }

    public function countBuyers() {
        return response()->json(User::where('is_admin', '0')->get()->count(), 200);
    }

    public function countVendors() {
        return response()->json(User::where('is_admin', '1')->get()->count(), 200);
    }
}
