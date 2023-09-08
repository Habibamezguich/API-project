<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prnt;
use App\Models\User;
use App\Http\Misc\Helpers\Base64Handler;
use App\Rules\ValidEncodedFile;




class ParentController extends Controller
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
            'situation' => 'required|in:salarié,retraité',
            'telephone' => 'required|string',
            'age' => 'required|integer',
            'photo' => [new ValidEncodedFile(Base64Handler::IMAGE_EXTS)], // to validate base64
            'genre' => 'required|in:Femme,Homme',
            'enfants' => 'required|in:1,2,3,4,5,6,7,8,9,10,12,13',
            'age_plus_jeune_enfant' => 'required|in:1,2,3,4,5,6,7,8,9,10,12,13',
            'traits_personnalite' => 'required|string',
            'activites_enfant' => 'required|string',
            'heures_babysitting' => 'required|string',
            'annonce' => 'required|string',
        ]);

        //create a user
        $user = new User();
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->role = 'parent';
        $user->save();
        try {

            // Handle file upload if a photo is provided
            if ($request->has('photo')) {
                //save file to user_images driver which defiend in filesystem as public/photos
                $photoPath = Base64Handler::storeFile($request->photo, 'users_images');
                $validatedData['photo'] = $photoPath;
            }

            $parent = new Prnt($validatedData);
            $parent->user_id = $user->id;

            // Save the parent record to the database
            $parent->save();

            // Return a response indicating success
            return response()->json(['message' => 'Parent created successfully'], 201);
        } catch (\Exception $e) {
            // Delete the user record if saving fails
            $user->delete();
            throw $e;
        }
    }

    public function getAllParentsById(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'parent_ids' => 'required|array', // An array of parent IDs
        ]);

        // Retrieve parents by their IDs
        $parents = Prnt::whereIn('id', $validatedData['parent_ids'])->get();

        // You can add additional logic here, such as formatting the response
        // or including related data if needed.

        // Return the list of parents as a JSON response
        return response()->json(['parents' => $parents]);
    }
    public function getAllParents()
    {
        // Retrieve all parents with their IDs
        $parents = User::select('id')->get();

        // Return the list of parents as a JSON response
        return response()->json(['parents' => $parents]);
    }



    public function getParentById($id)
    {

        // Find the parent by their ID
        $parent = User::find($id);

        if (!$parent) {
            return response()->json(['message' => 'Parent not found'], 404);
        }

        // Return the parent as a JSON response
        return response()->json(['parent' => $parent]);
    }
}
