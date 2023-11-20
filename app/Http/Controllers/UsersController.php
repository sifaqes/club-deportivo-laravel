<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class UsersController extends Controller
{
    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view(  'auth/register'  );
    }
    public function register(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view( 'auth/register');
    }

}
