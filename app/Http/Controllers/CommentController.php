<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Image;
use App\Like;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CommentRequest;
use App\Notification;
use App\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
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
    public function store(CommentRequest $request)
    {
        if (empty($request->content) && !$request->hasFile("image")) {
            return response()->json([
                'status' => 0,
                'message' => "Comment can't be empty",
            ]);
        } else {
            $comment = Comment::create([
                'content' => $request->content,
                'post_id' => $request->post_id,
                'user_id' => auth()->user()->id
            ]);

            if ($comment && $request->hasFile('image')) {
                Image::create([
                    'imageable_id' => $comment->id,
                    'imageable_type' => 'App\Comment',
                    'file_name' => $request->image->store('images/comments', 'public')
                ]);
            }

            return response()->json([
                'status' => 1,
                'message' => "Comment created successfuly",
                'comment_id' => $comment->id
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $comment->update($request->all());

        if ($request->hasFile('image')) {
            $newFileName = $request->image->store('images/' . $request->images_folder, 'public');

            if ($comment->image) {
                Storage::disk('public')->delete($comment->image->file_name);
            }

            $comment->image()->updateOrCreate(
                [],
                ['file_name' => $newFileName]
            );

            return response()->json([
                'status' => 1,
                'message' => "Comment updated successfuly",
                'image' => $newFileName
            ]);
        }

        if ($comment->image) {
            return response()->json([
                'status' => 1,
                'message' => "comment updated successfuly",
                'image' => $comment->image->file_name
            ]);
        } else {
            return response()->json([
                'status' => 1,
                'message' => "Comment updated successfuly",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        // Delete comment likes
        Like::where([
            ['likeable_type', '=', 'App\Comment'],
            ['likeable_id', '=', $comment->id]
        ])->delete();
        // Delete comment image
        if ($comment->image) {
            Storage::disk('public')->delete($comment->image->file_name);
            $comment->image->delete();
        }

        // Delete replies images and likes
        foreach ($comment->replies as $reply) {
            Like::where([
                ['likeable_type', '=', 'App\Reply'],
                ['likeable_id', '=', $reply->id]
            ])->delete();

            if ($reply->image) {
                Storage::disk('public')->delete($reply->image->file_name);
                $reply->image->delete();
            }
        }

        $comment->delete();

        return response()->json([
            'status' => 1,
            'message' => "Comment deleted successfuly",
        ]);
    }
}
