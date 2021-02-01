<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Post;
use App\Comment;
use App\Reply;
use App\Like;
use Faker\Generator as Faker;

$factory->define(Like::class, function (Faker $faker) {
    $user_id = User::all()->random()->id;
    $post_id = Post::all()->random()->id;
    $comment_id = Comment::all()->random()->id;
    $reply_id = Reply::all()->random()->id;

    $likeable_id = $faker->randomElement([$post_id, $comment_id, $reply_id]);

    if ($likeable_id == $post_id) {
        $likeable_type = 'App\Post';
    } elseif ($likeable_id == $comment_id) {
        $likeable_type = 'App\Comment';
    } elseif ($likeable_id == $reply_id) {
        $likeable_type = 'App\Reply';
    }

    return [
        'likeable_type' => $likeable_type,
        'likeable_id' => $likeable_id,
        'user_id' => $user_id
    ];
});
