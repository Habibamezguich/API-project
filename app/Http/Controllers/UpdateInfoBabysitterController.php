<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Babysitter;
use Illuminate\Support\Facades\Hash;


class UpdateInfoBabysitterController extends Controller
{
    public function updatebabysitter(Request $request, $id)
    {
        // Update the authenticated babysitter's basic information
        $babysitter = Babysitter::find($id);

        if (!$babysitter) {
            return ["result" => "User not found"];
        }
        // Update the user's basic information
        $babysitter->name = $request->name;
        $babysitter->prenom = $request->prenom;
        $babysitter->email = $request->email;
        $babysitter->adresse = $request->adresse;

  // Check if new password is provided and update password
  if ($request->has('new_password')) {
    $babysitter->password = Hash::make($request->new_password);
}
$result = $babysitter->save();
if ($result) {
    return ["result" => "User Updated"];
} else {
    return ["result" => "User Not Updated"];
}
}
    }
