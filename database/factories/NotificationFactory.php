<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Notification;
use App\User;
use Faker\Generator as Faker;

$factory->define(Notification::class, function (Faker $faker) {
    $from_id = User::all()->random()->id;
    $to_id = User::where('id', '!=', $from_id)->get()->random()->id;

    return [
        'content' => $faker->paragraph(4),
        'from_id' => $from_id,
        'to_id' => $to_id,
        'seen' => $faker->boolean,
    ];
});
