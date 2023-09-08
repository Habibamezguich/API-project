<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;

class PasswordResetRequestController extends Controller {

    public function sendEmail(Request $request){
        if(!$this->validateEmail($request->email)){

            return $this->failedResponse();
        }

        if($this->send($request->email)){

            return $this->successResponse();

        } else {
            return $this->failedResponse();
        }

    }

    public function send($email){
        try {
            $user = User::where('email', $email)->first();
            $token = $this->generateToken($email);
            if ($user) {
               Mail::to($email)->send(new ResetPasswordMail($user,$token)); // Pass the user to the mail template
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function generateToken($email){
        $isOtherToken = DB::table('password_reset_tokens')->where('email', $email)->first();
        if($isOtherToken) {
          return $isOtherToken->token;
        }
        $token = Str::random(80);

        $this->storeToken($token, $email);
        return $token;
      }
      public function storeToken($token, $email){

          DB::table('password_reset_tokens')->insert([
              'email' => $email,
              'token' => $token,
          ]);

      }

    public function validateEmail($email)
    {
        $exists = !!User::where('email', $email)->first();

        // Debugging statements
        if ($exists) {
            \Log::info("validateEmail: Email exists in database.");
        } else {
            \Log::info("validateEmail: Email does not exist in database.");
        }

        return $exists;
    }

    public function failedResponse(){
        return response()->json([
            'error'=>'email do not  exist'
        ], Response::HTTP_NOT_FOUND);
    }

    public function successResponse(){
        return response()->json([
            'data'=>'Votre email de réinitialisation a été envoyé. Veuillez vérifier votre boîte email.'
        ], Response::HTTP_OK);
    }
}
