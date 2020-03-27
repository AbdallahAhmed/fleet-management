<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class AuthController extends Controller
{
    /**
     * POST/GET admin/login
     * @param Request $request
     * @return Redirect
     */
    public function login(Request $request)
    {
        if ($request->method() == "POST") {
            $error = new MessageBag();
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required']);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput($request->all());
            }

            if (!Auth()->attempt([
                'email' => $request->get('email'),
                'password' => $request->get('password'),
                'role' => 1
            ])) {
                $error->add('invalid', "Incorrect Email or Password");
                return redirect()->back()->withErrors($error->messages())->withInput($request->all());
            }
            Auth::login(Auth::user(), $request->get('remember_me', 0) ? true : false);
            return redirect()->route('home');
        }
        return view('admin.login');
    }

    /**
     * POST/GET admin/register
     * @param Request $request
     * @return Redirect
     */
    public function register(Request $request)
    {
        if ($request->method() == "POST") {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'name' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput($request->all());
            }
            $user = new User();
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->role = 1;
            $user->password = $request->get('password');
            $user->api_token = $user->newApiToken();
            $user->save();
            Auth::login($user);
            return redirect()->route('home');
        }
        return view('admin.register');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
