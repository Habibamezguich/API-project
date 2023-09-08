<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Babysitter;

class CandidatesController extends Controller
{
    public function candidateprofile(Request $request)
    {
        $ville = $request->query('ville'); // Get the 'ville' query parameter

        $query = Babysitter::select('id', 'name', 'prenom', 'cv', 'certificat_secourisme', 'ville');

        // If a ville is provided, filter by it
        if ($ville) {
            $query->where('ville', $ville);
        }

        $candidates = $query->get();

        return response()->json(['candidates' => $candidates]);
    }
}

