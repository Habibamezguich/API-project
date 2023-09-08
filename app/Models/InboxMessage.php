<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InboxMessage extends Model
{
    use HasFactory;

    // Define two constants to represent different types of senders
    public  const USERSENDER = 1;
    public  const BABYSITTERSENDER = 2;

    // Specify the fields that can be filled when creating a new instance
    protected $fillable = [
        'inbox_id',   // The ID of the related inbox
        'sender',     // The type of sender (user or babysitter)
        'content',    // The content of the message
    ];

        // make an instance
    public function inbox()
    {
        return $this->belongsTo(Inbox::class);
    }

    // Method to determine who sent the message and return the sender
    public function sender()
    {
    // Check if the sender type is a user

        if ($this->sender == Self::USERSENDER) {
            //get inbox user
            return $this->inbox->user;
        } else if ($this->sender == SELF::BABYSITTERSENDER) {
            //get inbox babysitter
            return $this->inbox->babysitter;
        }
    }

        // Method to determine who received the message and return the receiver
    public function receiver()
    {
        if ($this->sender == Self::USERSENDER) {
            //get inbox babysitter
            return $this->inbox->babysitter;
        } else if ($this->sender == SELF::BABYSITTERSENDER) {
        // If the sender is not a user, check if it's a babysitter
            return $this->inbox->user;
        }
    }
}
