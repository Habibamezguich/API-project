<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;


class ChangePasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8', // no need for password confirmation validation , confimred refer
            'token' => 'required'
        ]);

        $tokenData = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->where('email',$request->email)
            ->first();

        if (!$tokenData) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }


        $user->password=$request->password;
        $user->password_confirmation=$request->password;
        $user->save();


        // Delete the token record
        DB::table('password_reset_tokens')->where('token', $request->token)->delete();

        return response()->json(['message' => 'Password reset successful']);
    }
}

