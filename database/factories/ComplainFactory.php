<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use App\Complain;
use App\Post;
use App\Reply;
use App\User;
use Faker\Generator as Faker;

$factory->define(Complain::class, function (Faker $faker) {
    $user_id = User::all()->random()->id;
    $post_id = Post::all()->random()->id;
    $comment_id = Comment::all()->random()->id;
    $reply_id = Reply::all()->random()->id;

    $complainable_id = $faker->randomElement([$user_id, $post_id, $comment_id, $reply_id]);

    if ($complainable_id == $user_id) {
        $complainable_type = 'App\User';
    } elseif ($complainable_id == $post_id) {
        $complainable_type = 'App\Post';
    } elseif ($complainable_id == $comment_id) {
        $complainable_type = 'App\Comment';
    } else {
        $complainable_type = 'App\Reply';
    }

    return [
        'complainable_type' => $complainable_type,
        'complainable_id' => $complainable_id,
        'content' => $faker->paragraph,
        'solved' => $faker->boolean
    ];
});
