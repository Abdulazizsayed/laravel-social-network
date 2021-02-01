<?php

namespace App\Http\Controllers;

use App\Image;
use App\Message;
use App\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return view('users.messages')->with([
            'messages' => Message::whereIn('sender_id', [$user->id, auth()->user()->id])->whereIn('receiver_id', [$user->id, auth()->user()->id])->orderBy('created_at', 'desc')->get(),
            'lastPeopleMessagedMe' => Message::where('sender_id', auth()->user()->id)->orWhere('receiver_id', auth()->user()->id)->orderBy('created_at', 'desc')->get()->map(function ($message) {
                return $message->sender->id == auth()->user()->id ? $message->receiver : $message->sender;
            })->unique(),
            'user' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (empty($request->content) && !$request->hasFile("image")) {
            return response()->json([
                'status' => 0,
                'message' => "Message can't be empty",
            ]);
        } else {
            $message = Message::create([
                'content' => $request->content,
                'sender_id' => auth()->user()->id,
                'receiver_id' => $request->receiver_id
            ]);

            if ($message && $request->hasFile('image')) {
                $image = $request->image->store('images/messages', 'public');

                Image::create([
                    'imageable_id' => $message->id,
                    'imageable_type' => 'App\Message',
                    'file_name' => $image
                ]);

                return response()->json([
                    'status' => 1,
                    'message' => "Message sent successfuly",
                    'image' => $image
                ]);
            }

            return response()->json([
                'status' => 1,
                'message' => "Message sent successfuly",
                'image' => ''
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
