<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Comment;
use App\Reply;
use Faker\Generator as Faker;

$factory->define(Reply::class, function (Faker $faker) {
    return [
        'content' => $faker->paragraph,
        'comment_id' => Comment::all()->random()->id,
        'user_id' => User::all()->random()->id
    ];
});
