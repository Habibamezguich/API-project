<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;
use App\Models\Inbox;
use App\Models\User;
use App\Models\BabySitter;
use App\Models\InboxMessage;

class InboxController extends Controller
{
    // Methods for handling inbox-related operations

    public function index(Request $request)
    {
        //we get the user
        $user = $request->user();
        // we get its inboxes
        $inboxes = $user->inboxes()->with(['user', 'babysitter'])->get();
        return response()->json($inboxes);
    }
    //display the details of a specific inbox
    public function show(Request $request, BabySitter $receiver)
    {
        $inbox = $request->user()->inboxes()->where('babysitter_id', $receiver->id)->first();

        if ($inbox == null) {
            $inbox = $request->user()->inboxes()->create([
                'babysitter_id' => $receiver->id,
            ]);
        }
        $inbox->save();
        //here we get the messages associated with inbox using id
        return MessageResource::collection($inbox->messages()->orderBy('created_at', 'desc')->get());
    }
    public function showbb(Request $request, User $receiver)
    {
        // $receiver i sa parent (i logged as babysitter)
        // so here we will get the babysitter inbox related to the parent
        $inbox = $request->user()->inboxes()->where('user_id', $receiver->id)->first();
        // if there is no inbox it will create an empty one
        if ($inbox == null) {
            $inbox = $request->user()->inboxes()->create([
                'user_id' => $receiver->id,
            ]);
        }
        $inbox->save();

        //here we get the messages associated with inbox using id using Message resource
        return MessageResource::collection($inbox->messages()->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request,  $receiver)
    {
        // Handle the process of initiating an inbox or sending messages from a babysitter to a parent
        // this function is to init the inbox or send any upcoming messages
        $user = $request->user();
        //validation
        $this->validate($request, [
            'content' => 'required|string',
        ]);

        //here we know the the reciever should be babysiter becasuse we already restrict the route for babysitters
        $receiver = BabySitter::findOrFail($receiver);
        if ($user == null) return response()->json(['message' => 'user not found'], 404);

        // create inbox if not exist and save message
        $inbox = $user->inboxes()
            ->where('babysitter_id', $receiver->id)
            ->first();
        // here if there is no old inbox we create one
        if ($inbox == null) {
            $inbox = $user->inboxes()->create([
                'babysitter_id' => $receiver->id,
            ]);
        }

        //save content
        // here we used the USERSENDER type because this function is from parent
        $message = $inbox->messages()->create([
            'sender' => InboxMessage::USERSENDER,
            'content' => $request->content,
        ]);

        return new MessageResource($message);
    }
    public function storebb(Request $request,  $receiver)
    {
        // this function is same as the above but for babysitters, and the recievers are users
        $babysitter = $request->user();
        //validation
        $this->validate($request, [
            'content' => 'required|string',
        ]);

        $receiver = User::findOrFail($receiver);
        // Check if the babysitter exists
        if ($babysitter == null)
            return response()->json(['message' => 'user not found'], 404);


        // create inbox if not exist and save message
        $inbox = $babysitter->inboxes()
            ->where('user_id', $receiver->id)
            ->first();
        if ($inbox == null) {
            $inbox = $babysitter->inboxes()->create([
                'user_id' => $receiver->id,
            ]);
        }

        //save message with the sender as BABYSITTERSENDER type
        $message = $inbox->messages()->create([
            'sender' => InboxMessage::BABYSITTERSENDER,
            'content' => $request->content,
        ]);

        return new MessageResource($message);
    }
}
