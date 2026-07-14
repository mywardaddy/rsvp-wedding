<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class RegisteredUserController extends Controller
{
    /**
     * Redirect to pricing section instead of showing registration form.
     * Users must subscribe through the order flow to get an account.
     */
    public function create(): RedirectResponse
    {
        return redirect(url('/#pricing'));
    }
}
