<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
class CheckStatus
{
    public function handle(Request $request, Closure $next)
    {

        if(isset(auth()->user()->roles->first()->name) and env('APP_ENV') != 'local'){

            if( strtoupper(auth()->user()->roles->first()->name) != 'ROOT' and $request->is('painel/logs')){
                return response()->json(['error' => 'Você não tem permissão para acessar este recurso'], 401);
            }

            if (auth()->user()->status != 'active'){
                return redirect()->route('home.index')->with('error', 'Seu usuário está bloqueado na plataforma.');
            }

        }

        return $next($request);
        return redirect()->back()->withError('Sua conta está inativa');

    }
}
