<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    use HasFactory;
    protected $fillable = ['description','ville','salary','date','status'];


    public function user()
    {
        // we also created a relation here
        return $this->belongsTo(User::class);
    }

}

