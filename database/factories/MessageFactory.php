<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Message;
use App\User;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {

    $sender_id = User::all()->random()->id;
    $receiver_id = User::where('id', '!=', $sender_id)->get()->random()->id;

    return [
        'content' => $faker->paragraph(4),
        'sender_id' => $sender_id,
        'receiver_id' => $receiver_id,
        'seen' => $faker->boolean,
    ];
});
