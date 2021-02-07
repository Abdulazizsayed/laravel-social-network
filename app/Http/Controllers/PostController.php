<?php

namespace App\Http\Controllers;

use App\Post;
use App\Image;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Like;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
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
    public function store(PostRequest $request)
    {
        if (empty($request->content) && !$request->hasFile("image")) {
            return response()->json([
                'status' => 0,
                'message' => "Post can't be empty",
            ]);
        } else {
            $post = Post::create([
                'content' => $request->content,
                'user_id' => auth()->user()->id
            ]);

            $image = '';

            if ($post && $request->hasFile('image')) {
                $image = $request->image->store('images/posts', 'public');
                Image::create([
                    'imageable_id' => $post->id,
                    'imageable_type' => 'App\Post',
                    'file_name' => $image
                ]);
            }

            $profile_image = '';
            if (auth()->user()->image) {
                $profile_image = auth()->user()->image->filename;
            }

            return response()->json([
                'status' => 1,
                'message' => "Post created successfuly",
                'image' => $image,
                'profile_image' => $profile_image
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('posts.show')->with([
            'post' => $post,
            'rand' => rand(),
            'rand2' => rand(),
            'rand3' => rand(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->all());

        if ($request->hasFile('image')) {
            $newFileName = $request->image->store('images/' . $request->images_folder, 'public');

            if ($post->image) {
                Storage::disk('public')->delete($post->image->file_name);
            }

            $post->image()->updateOrCreate(
                [],
                ['file_name' => $newFileName]
            );

            return response()->json([
                'status' => 1,
                'message' => "Post updated successfuly",
                'image' => $newFileName
            ]);
        }

        if ($post->image) {
            return response()->json([
                'status' => 1,
                'message' => "Post updated successfuly",
                'image' => $post->image->file_name
            ]);
        } else {
            return response()->json([
                'status' => 1,
                'message' => "Post updated successfuly",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // Delete post likes
        Like::where([
            ['likeable_type', '=', 'App\Post'],
            ['likeable_id', '=', $post->id]
        ])->delete();
        // Delete post image
        if ($post->image) {
            Storage::disk('public')->delete($post->image->file_name);
            $post->image->delete();
        }

        // Delete comments and replies images and likes
        foreach ($post->comments as $comment) {
            Like::where([
                ['likeable_type', '=', 'App\Comment'],
                ['likeable_id', '=', $comment->id]
            ])->delete();

            if ($comment->image) {
                Storage::disk('public')->delete($comment->image->file_name);
                $comment->image->delete();
            }

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
        }

        $post->delete();

        return response()->json([
            'status' => 1,
            'message' => "Post deleted successfuly",
        ]);
    }
}
