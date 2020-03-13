<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class AuthController extends Controller
{
    public function login(Request $request){
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
            return redirect()->route('home');
        }
        return view('admin.login');
    }

    public function logout(){
        Auth::logout();

        return redirect()->route('login');
    }
}
