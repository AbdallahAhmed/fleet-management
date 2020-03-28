<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\Facades\JWTAuth;
use File;

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
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return $this->errorResponse(["Incorrect Email or Password."], 401);
        }

        $user = auth()->user();

        return $this->response(['user' => $user, 'token' => $token]);
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
        $user->save();

        $token = JWTAuth::fromUser($user);

        return $this->response(['user' => $user, 'token' => $token]);
    }

    public function me(Request $request)
    {
        try {

            $user = auth()->claims(['role'])->userOrFail();

        } catch (UserNotDefinedException $e) {
            return $this->errorResponse([$e->getMessage(), 400]);
        }
        return $this->response(['user' => $user]);
    }

    public function updateAccount(Request $request)
    {
        try {
            $user = auth()->claims(['role'])->userOrFail();

        } catch (UserNotDefinedException $e) {
            return $this->errorResponse([$e->getMessage(), 400]);
        }
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $user->id . ',id',
            'password' => 'min:6',
            'name' => 'required|max:255',
            'image' => 'mimes:png,jpg,jpeg,svg,gif'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(($validator->errors()->all()));
        }

        $user->email = $request->get('email');
        $user->name = $request->get('name');
        if ($password = $request->get('password'))
            $user->password = $password;
        if ($image = $request->file('image')) {
            $image_name = $image->getClientOriginalName();
            $image_directory = public_path("uploads") . date("/Y/m");
            File::makeDirectory($image_directory, 0777, true, true);
            $image->move($image_directory, $image_name);
            $user->avatar = url('uploads').date("/Y/m/").''.$image_name;
        }
        $user->save();
        $token = JWTAuth::fromUser($user);
        return $this->response(['user' => $user, 'token' => $token]);
    }
}
