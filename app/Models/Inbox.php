<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inbox extends Model
{
    use HasFactory;

    // fields that can be filled when creating a new instance
    protected $fillable = [
        'user_id',
        'babysitter_id',
    ];

    // Define a relationship with the "User" model, indicating this inbox belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define a relationship with the "Babysitter" model, indicating this inbox belongs to a babysitter
    public function babysitter()
    {
        return $this->belongsTo(Babysitter::class, 'babysitter_id');
    }

        // Define a one-to-many relationship with the "InboxMessage"
        //An inbox can have multiple messages, but each message belongs to only one inbox.
    public function messages()
    {
        return $this->hasMany(InboxMessage::class, 'inbox_id');
    }
}
