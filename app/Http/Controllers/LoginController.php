<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\Login\RememberMeExpiration;
use App\Models\User;
use App\Models\Sessions;
use Log;

use \Carbon\Carbon;
use Notification;
use App\Notifications\AlertNotification;

class LoginController extends Controller
{
    use RememberMeExpiration;

    public function show()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        try {

            $credentials = $request->getCredentials();

            if(!Auth::validate($credentials)){
                return redirect()->to('login')->with('error', 'Login ou senha inválidos.');
            }

            $user = Auth::getProvider()->retrieveByCredentials($credentials);
            Auth::login($user, $request->get('remember'));

            if($request->get('remember')){
                $this->setRememberMeExpiration($user);
            }

            $user = User::with('sessions')->whereId($user->id)->get();

            try {

                if( $user->block_mail != 1 ){
                    Notification::send($user, new AlertNotification([
                        'subject' => '🔓 Aviso de novo login',
                        'title' => 'Notamos que seu e-mail '.$user->email.' foi utilizado para fazer login em nossa plataforma hoje com o IP '.self::getIp().' no dia '.Carbon::now()->format('d/m/Y H:i:s'),
                        'body' => 'Esse é apenas um aviso de login, não se preocupe.',
                        'button' => 'Não foi você?',
                        'footer' => '',
                        'actionURL' => url('/painel/users/'.$user->id.'/edit'),
                    ]));
                }

            } catch (\Exception $e) {
                Log::info(['Erro ao enviar e-mail!']);
            }

            //Log::info(['Usuáro logado na plataforma!', $user->username]);

            $user = User::whereId($request->user()->id);
            $user->update([ 'last_login' => Carbon::now() ]);


            $user = User::whereId($request->user()->id)->first();
            return $this->authenticated($request, $user);

        } catch (\Throwable $th) {
            Log::critical(['Falha no login', $th->getMessage()]);
            return redirect()->intended('/painel')->with('error', 'Falha durante o login, tente novamente mais tarde.'.$th->getMessage());
        }

    }

    protected function authenticated($user)
    {
        return redirect()->intended('/painel')->with('success', 'Seja bem vindo(a) '.$user->username.'!');
    }
}
