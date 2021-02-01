<?php

namespace App\Http\Controllers;

use App\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggleLike(Request $request)
    {
        $like = Like::where([
            ['likeable_id', '=', $request->likeable_id],
            ['likeable_type', '=', $request->likeable_type],
            ['user_id', '=', auth()->user()->id]
        ]);

        if ($like->get()->count() > 0) {
            $like->delete();
            return response()->json([
                'status' => 1,
                'message' => "Unliked successfuly"
            ]);
        } else {
            Like::create([
                'likeable_id' => $request->likeable_id,
                'likeable_type' => $request->likeable_type,
                'user_id' => auth()->user()->id
            ]);
            return response()->json([
                'status' => 1,
                'message' => "Liked successfuly"
            ]);
        }
    }
}
