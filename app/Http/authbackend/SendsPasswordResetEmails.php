<?php

namespace app\Http\authbackend;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Mail;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait SendsPasswordResetEmails
{
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request, $api = null)
    {
        $appUrl = url('/');
        $user = \App\Models\User::get();
        $request->validate([
              'email' => 'required',
              'g-recaptcha-response' => 'required'
        ]);
        $token = Str::random(64);
        
        $sendEmail = 0;
        foreach ($user as $userData) {
            $emailId = $userData['email'];
            if (\Hash::check($request->email, $emailId) == 1) {
                $sendEmail=1;
            }
        }
        if ($sendEmail == 1) {
                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);

                $regards = str_replace('-', ' ', env('APP_EMAIL_REGARDS'));

                Mail::send('auth.passwords.reset-mail', ['appUrl' => $appUrl, 'token' => $token, 'email' => $request->email, 'regards' => $regards], function($message) use($request){
                    $message->to($request->email);
                    $message->subject('Reset Password');
                });
                if ($api == 1) {
                    $return = 1;
                } else {
                    Log::info(' We have e-mailed your password reset link! for ' .$request->email);
                    return back()->with('success', 'We have e-mailed your password reset link!');
                }
            } else {
                if ($api == 1) {
                    $return = 0;
                } else {
                    Log::info('Entered email is not found for ' .$request->email);
                    return back()->with('failed', 'Entered email is not found!');
                }
            }
            return $return;
    }


    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'g-recaptcha-response' => 'required',
        ]);
    }

    /**
     * Get the needed authentication credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('email');
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
        return $request->wantsJson()
                    ? new JsonResponse(['message' => trans($response)], 200)
                    : back()->with('status', trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                'email' => [trans($response)],
            ]);
        }

        return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans($response)]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }
}
