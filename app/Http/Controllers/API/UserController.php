<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ShowsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class UserController extends ApiController
{

    public function getFavorites(Request $request)
    {
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $favorites = \DB::table('users_shows')->where('user_id', '=', auth()->id())->offset($offset)->limit($limit)->get();
        $shows = [];
        $show = new ShowsController();
        foreach ($favorites as $favorite) {
            $shows [] = $show->getShow($favorite->show_id);
        }
        return $this->response(['shows' => $shows]);
    }

    public function addFavorite($id)
    {
        $user = auth()->user();
        $show = DB::table('users_shows')->where([
            'user_id' => $user->id,
            'show_id' => $id
        ])->first();
        if (!$show) {
            DB::table('users_shows')->insert(['user_id' => $user->id, 'show_id' => $id]);
        }
        return $this->response(['shows_count' => $user->shows_count, 'shows_ids' => $user->shows_ids]);
    }

    public function removeFavorite($id)
    {
        $user = auth()->user();
        DB::table('users_shows')->where([
            'user_id' => $user->id,
            'show_id' => $id
        ])->delete();

        return $this->response(['shows_count' => $user->shows_count, 'shows_ids' => $user->shows_ids]);
    }

}
