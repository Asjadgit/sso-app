<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\CentralUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $user = CentralUser::where('email',$request->email)->first();
        if(!$user || Hash::check($request->password, $user->password)){
            return redirect()->back('error','Credentials do no match');
        }

        Auth::login();
    }
}
