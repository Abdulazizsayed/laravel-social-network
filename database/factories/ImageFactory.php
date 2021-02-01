<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use App\Comment;
use App\Complain;
use App\Message;
use App\Post;
use App\Reply;
use App\User;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {
    $user_id = User::all()->random()->id;
    $post_id = Post::all()->random()->id;
    $comment_id = Comment::all()->random()->id;
    $reply_id = Reply::all()->random()->id;
    $complain_id = Complain::all()->random()->id;
    $message_id = Message::all()->random()->id;

    $imageable_id = $faker->randomElement([$user_id, $post_id, $comment_id, $reply_id, $complain_id, $message_id]);

    if ($imageable_id == $user_id) {
        $imageable_type = 'App\User';
        $filename = 'images/users/profile/' . $faker->randomElement(['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg', '9.jpg', '10.jpg',]);
    } elseif ($imageable_id == $post_id) {
        $imageable_type = 'App\Post';
        $filename = 'images/posts/' . $faker->randomElement(['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg', '9.jpg', '10.jpg',]);
    } elseif ($imageable_id == $comment_id) {
        $imageable_type = 'App\Comment';
        $filename = 'images/comments/' . $faker->randomElement(['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg', '9.jpg', '10.jpg',]);
    } elseif ($imageable_id == $reply_id) {
        $imageable_type = 'App\Reply';
        $filename = 'images/replies/' . $faker->randomElement(['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg', '9.jpg', '10.jpg',]);
    } elseif ($imageable_id == $complain_id) {
        $imageable_type = 'App\Complain';
        $filename = 'images/complains/' . $faker->randomElement(['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg', '9.jpg', '10.jpg',]);
    } elseif ($imageable_id == $message_id) {
        $imageable_type = 'App\Message';
        $filename = 'images/messages/' . $faker->randomElement(['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg', '9.jpg', '10.jpg',]);
    }


    return [
        'imageable_type' => $imageable_type,
        'imageable_id' => $imageable_id,
        'file_name' => $filename,
    ];
});
