<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'verified']);
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $following_users_ids = auth()->user()->following()->get()->map(function ($user) {
            return $user->id;
        });

        $following_users_ids->push(auth()->user()->id);

        $posts = Post::whereIn('user_id', $following_users_ids)->orderBy('created_at', 'desc')->paginate(5);

        return view('home')->with([
            'posts' => $posts,
            'following' => auth()->user()->following()->limit(20)->inRandomOrder()->get(),
            'suggestions' => User::all()->random(20)->except(auth()->user()->following->map(function ($user) {
                return $user->id;
            })->toArray()),
            'rand' => rand(),
            'rand2' => rand(),
            'rand3' => rand(),
        ]);
    }
}
