<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    /**
     * POST /auth
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $response['errors'] = $validator->errors()->all();
            return response()->json($response, '400');
        }

        $isAuthed = Auth::guard('api')->once([
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ]);

        if (!$isAuthed) {
            return $this->errorResponse(["Incorrect Email or Password."], 400);
        }

        $user = Auth::guard('api')->user();
        $user->api_token = User::newApiToken();
        $user->save();

        return $this->response(['user' => $user, 'token' => $user->api_token]);
    }

    /**
     * POST /register
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(($validator->errors()->all()));
        }

        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = $request->get('password');
        $user->api_token = $user->newApiToken();
        $user->save();

        return $this->response(['user' => $user, 'token' => $user->api_token]);
    }
}
