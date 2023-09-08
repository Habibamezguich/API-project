<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateInfoParentController extends Controller
{
    public function updateparent(Request $request, $id)
    {
        // Update the authenticated user's basic information
        $user = User::find($id);

        if (!$user) {
            return ["result" => "User not found"];
        }

        // Update the user's basic information
        $user->name = $request->name;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->adresse = $request->adresse;

        // Check if new password is provided and update password
        if ($request->has('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $result = $user->save();
        if ($result) {
            return ["result" => "User Updated"];
        } else {
            return ["result" => "User Not Updated"];
        }
    }
}
