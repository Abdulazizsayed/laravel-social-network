<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyRequest;
use App\Reply;
use App\Image;
use App\Like;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(ReplyRequest $request)
    {
        if (empty($request->content) && !$request->hasFile("image")) {
            return response()->json([
                'status' => 0,
                'message' => "Reply can't be empty",
            ]);
        } else {
            $reply = Reply::create([
                'content' => $request->content,
                'comment_id' => $request->comment_id,
                'user_id' => auth()->user()->id
            ]);

            if ($reply && $request->hasFile('image')) {
                Image::create([
                    'imageable_id' => $reply->id,
                    'imageable_type' => 'App\Reply',
                    'file_name' => $request->image->store('images/replies', 'public')
                ]);
            }

            return response()->json([
                'status' => 1,
                'message' => "Reply created successfuly",
                'reply_id' => $reply->id
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {
        $reply->update($request->all());

        if ($request->hasFile('image')) {
            $newFileName = $request->image->store('images/' . $request->images_folder, 'public');

            if ($reply->image) {
                Storage::disk('public')->delete($reply->image->file_name);
            }

            $reply->image()->updateOrCreate(
                [],
                ['file_name' => $newFileName]
            );

            return response()->json([
                'status' => 1,
                'message' => "Reply updated successfuly",
                'image' => $newFileName
            ]);
        }

        if ($reply->image) {
            return response()->json([
                'status' => 1,
                'message' => "Reply updated successfuly",
                'image' => $reply->image->file_name
            ]);
        } else {
            return response()->json([
                'status' => 1,
                'message' => "Reply updated successfuly",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        // Delete reply likes
        Like::where([
            ['likeable_type', '=', 'App\Reply'],
            ['likeable_id', '=', $reply->id]
        ])->delete();
        // Delete reply image
        if ($reply->image) {
            Storage::disk('public')->delete($reply->image->file_name);
            $reply->image->delete();
        }

        $reply->delete();

        return response()->json([
            'status' => 1,
            'message' => "Reply deleted successfuly",
        ]);
    }
}
