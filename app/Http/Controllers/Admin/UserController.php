<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CentralUser;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = CentralUser::all();

        // return view();
    }
}
