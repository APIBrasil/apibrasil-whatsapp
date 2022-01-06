<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    /**
     * Display register page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * Handle account registration request
     *
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        try {

            $user = User::create($request->validated());
            auth()->login($user);
            return redirect('/painel')->with('success', "Sua conta foi criada com sucesso!");

        } catch (\Throwable $th) {
            \Log::critical(['Falha no register', $th->getMessage()]);
        }

    }
}
