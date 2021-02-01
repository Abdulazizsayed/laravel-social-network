<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $posts = $user->posts()->orderBy('created_at', 'desc')->paginate(10);

        return view('users.profile')->with([
            'user' => $user,
            'posts' => $posts,
            'rand' => rand(),
            'rand2' => rand(),
            'rand3' => rand(),
        ]);
    }

    public function edit()
    {
        return view('users.editProfile');
    }

    public function update(ProfileRequest $request)
    {
        // dd($request->password);
        $me = auth()->user();

        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request->get('password'))]);
            $me->update($request->all());
        } else {
            $me->update($request->except('password'));
        }


        if ($request->hasFile('image')) {

            if ($me->image) {
                Storage::disk('public')->delete($me->image->file_name);
            }

            $me->image()->updateOrCreate(
                [],
                ['file_name' => $request->image->store('images/users/profile/', 'public')]
            );
        }

        return redirect('users/editProfile')->with('status', 'Your profile updated successfuly');
    }
}
