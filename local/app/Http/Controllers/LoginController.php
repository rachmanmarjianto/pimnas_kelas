<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        echo $username." ".$password;die;

        // if ($username == 'admin' && $password == 'admin') {
        //     return view('welcome');
        // } else {
        //     return view('login', ['error' => 'Invalid username or password']);
        // }
    }
}
