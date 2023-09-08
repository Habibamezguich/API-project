<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\Request;

class OffreController extends Controller
{
    public function index()
    {
        // we are returning the data with ('user') since its arelation so no need for json resource
        return Offre::select('id', 'description', 'ville', 'salary', 'date', 'status', 'user_id')->with('user')->get();
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required',
            'ville' => 'required',
            'salary' => 'required',
            'date' => 'required',
            'status' => 'required',
        ]);



        $offer = new Offre();
        $offer->description = $validated['description'];
        $offer->ville = $validated['ville'];
        $offer->salary = $validated['salary'];
        $offer->date = $validated['date'];
        $offer->status = $validated['status'];
        //because we added the route to middleware now it will be restricted to parents
        // as well the user data will be avaiable to use , so here we insert the creator id
        $offer->user_id = $request->user()->id;
        $offer->save();

        return $offer;
    }
    public function show(Offre $offre)
    {

        $parentID = $offre->User_id; // Use the correct relationship name (user_id)

        return response()->json([
            'offre' => [
                'id' => $offre->id,
                'description' => $offre->description,
                'ville' => $offre->ville,
                'salary' => $offre->salary,
                'date' => $offre->date,
                'status' => $offre->status,
                'created_at' => $offre->created_at,
                'updated_at' => $offre->updated_at,
                'parent_id' => $parentID, // Include the parent ID

            ]
        ]);
    }




    public function update(Request $request, Offre $offre)
    {
        $validated = $request->validate([
            'description' => 'required',
            'ville' => 'required',
            'salary' => 'required',
            'date' => 'required',
            'status' => 'required',
        ]);

        $offre->update($validated);

        return response()->json([
            'message' => 'Offre updated successfully'
        ]);
    }




    public function destroy(Offre $offre)
    {
        $offre->delete();

        return response()->json([
            'message' => 'Offre deleted successfully'
        ]);
    }



    public function edit(Offre $offre)
    {
        return response()->json([
            'offre' => $offre
        ]);
    }
}
