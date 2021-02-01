<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = factory('App\User', 20)->create();
        factory('App\Post', 50)->create();
        factory('App\Comment', 70)->create();
        factory('App\Reply', 120)->create();
        factory('App\Message', 150)->create();
        factory('App\Complain', 10)->create();
        factory('App\Notification', 100)->create();
        factory('App\Image', 200)->create();
        factory('App\Like', 2000)->create();

        foreach ($users as $user) {
            $users_ids = User::where('id', '!=', $user->id)->limit(4)->pluck('id')->toArray();

            $user->following()->sync($users_ids);
        }
    }
}
