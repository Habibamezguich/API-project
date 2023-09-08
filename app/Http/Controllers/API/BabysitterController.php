<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bbsitter;
use App\Models\User;
use App\Http\Misc\Helpers\Base64Handler;
use App\Rules\ValidEncodedFile;


class BabysitterController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
        'nom' => 'required|string',
        'prenom' => 'required|string',
        'email' => 'required|email|unique:users,email|confirmed',
        'password' => 'required|string|confirmed',
        'adresse' => 'required|string',
        'telephone' => 'required|string',
        'age' => 'required|integer',
        'photo' => [new ValidEncodedFile(Base64Handler::IMAGE_EXTS)], // to validate base64
        'experience' => 'required|in:oui,non',
        'type_experience' => 'nullable|in:familial,professionnel',
        'age_enfants' => 'nullable|integer',
        'numeros_familles' => 'nullable|string',
        'description' => 'required|string',
        'cv' => 'nullable|string',
        'certificat_secourisme' => 'nullable|string',
        'cin' => 'nullable|string',
        'attestation_presence' => 'nullable|string',

    ]);

 //create a user
 $user = new User();
 $user->email = $validatedData['email'];
 $user->password = bcrypt($validatedData['password']);
 $user->role = 'babysitter';
 $user->save();
 try {


        // Handle file upload if a photo is provided
        if ($request->has('photo')) {
            //save file to user_images driver which defiend in filesystem as public/photos
            $photoPath =Base64Handler::storeFile($request->photo,'users_images');
            $validatedData['photo'] = $photoPath;
        }
        $babysitter = new Bbsitter($validatedData);
        $babysitter->user_id = $user->id;

    // Save the parent record to the database
        $babysitter->save();

        // Return a response indicating success
        return response()->json(['message' => 'Babysitter created successfully'], 201);

    }
    catch (\Exception $e) {
        // Delete the user record if saving fails
        $user->delete();
        throw $e;
    }
    }
}

