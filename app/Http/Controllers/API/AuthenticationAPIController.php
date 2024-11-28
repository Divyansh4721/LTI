<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Mail;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\authbackend\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Log;

class AuthenticationAPIController extends Controller
{
    use SendsPasswordResetEmails;
    /**
        * @OA\Post(
        * path="/api/login",
        * operationId="authLogin",
        * tags={"Login"},
        * summary="User Login",
        * description="Login User Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"username", "password"},
        *               @OA\Property(property="username", type="text"),
        *               @OA\Property(property="password", type="password")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function login(Request $request){
        $input = $request->all();

        $validation = Validator::make($input,[
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()], 422);
        }

        if (Auth::attempt(['username' => $input['username'], 'password' => $input['password']])) {
            $user = Auth::user();
            $token = $user->createToken('MGH49-Token')->accessToken;
            Log::info($input['username']. ' has logged in successfully');
            return response()->json(['token' => $token]);
        } else {
            Log::info('login attempt by ' .$input['username']. ' has been failed');
            return response()->json(['error' => 'Username or Password not matching'], 401);
        }

    }
    
    /**
        * @OA\Get(
        * path="/api/dashboard",
        * operationId="dashboard",
        * tags={"dashboard"},
        * security={{"bearer_token": {} }},
        * summary="User dashboard",
        * description="dashboard",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(),
        *        ),
        *    ),
        *
        *      @OA\Response(
        *          response=201,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function dashboard(Request $request) {
        $user = Auth::guard('api')->user();

        return response()->json(['user' => $user]);

    }

    /**
        * @OA\Post(
        * path="/api/forgot-password",
        * operationId="ForgotPassword",
        * tags={"Login"},
        * summary="Forgot Password",
        * description="Forgot Password",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email", "password"},
        *               @OA\Property(property="email", type="email"),
        *               @OA\Property(property="password", type="password")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="We have e-mailed your password reset link!",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="We have e-mailed your password reset link!",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function password_reset_link(Request $request)
    {
        $response =  $this->sendResetLinkEmail($request, 1);
        if ($response == 1) {
            Log::info(' We have e-mailed your password reset link! for ' .$request->email);
            return 'We have e-mailed your password reset link!';
        } else {
            Log::info('Entered email is not found for ' .$request->email);
            return response()->json(['error' => 'Entered email is not found!'], 401);
        }
    }

    public function logout(Request $request) {
        $user = Auth::user()->token();
        $user->revoke();
        return response()->json(['success' => 'you had been logged out successfull']);
    }

}
