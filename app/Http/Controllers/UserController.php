<?php

namespace App\Http\Controllers;

use App\Notification;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function toggleFollow(User $user)
    {
        if (auth()->user()->following()->get()->contains($user)) {
            auth()->user()->following()->detach($user);
            return response()->json([
                'status' => 0,
                'message' => "User unfollowed successfuly",
            ]);
        } else {
            auth()->user()->following()->attach($user);
            Notification::create([
                'content' => auth()->user()->name . ' followed you',
                'from_id' => auth()->user()->id,
                'to_id' => $user->id,
                'link' => 'http://127.0.0.1:8000/users/profile/' . $user->id
            ]);
            return response()->json([
                'status' => 1,
                'message' => "User followed successfuly",
            ]);
        }
    }

    public function searchFollowed(Request $request)
    {
        $users = User::where('name', 'LIKE', '%' . $request->search_input . '%')->orWhere('nickname', 'LIKE', '%' . $request->search_input . '%')->get()->intersect(auth()->user()->following);
        return response()->json([
            'users' => $users->map(function ($user) {
                if ($user->image) {
                    return [$user->id, $user->name, $user->image->file_name];
                }
                return [$user->id, $user->name, 'images/users/profile/default_user_photo.jpg'];
            })
            // 'users' => $users->get()
        ]);
    }

    public function searchUnfollowed(Request $request)
    {
        $users = User::where('name', 'LIKE', '%' . $request->search_input . '%')->orWhere('nickname', 'LIKE', '%' . $request->search_input . '%')->get()->filter(function ($value) {
            return !auth()->user()->following()->get()->contains($value) && $value != auth()->user();
        });
        return response()->json([
            'users' => $users->map(function ($user) {
                if ($user->image) {
                    return [$user->id, $user->name, $user->image->file_name, csrf_token()];
                }
                return [$user->id, $user->name, 'images/users/profile/default_user_photo.jpg', csrf_token()];
            })
            // 'users' => $users->get()
        ]);
    }

    public function editProfile(Request $request)
    {
        return "hello from edit";
    }
}
