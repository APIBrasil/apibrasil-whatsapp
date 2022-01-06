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

            //$session = Sessions::where('user_id', $request->user()->id)->first();
            $mensagem = "";
            if(isset($user->sessions)){

                foreach($user->sessions as $session){
                    json_decode($this->requestIntegracao($request, $session, [
                        'session' => $session->session_name,
                    ], 'start'), true, JSON_UNESCAPED_UNICODE);
                }

                $mensagem = ', aproveitamos para lhe avisar que '.$user->sessions->count() > 1 ? 'conexões' : 'conexão'.' já foram iniciadas, vá em sessões para gerencia-la.';

            }else{

                try {

                    $user = User::where('email', $request->user()->email)->first();

                    $end_date = isset($user->last_activity) ? $user->last_activity : Carbon::now()->format('Y-m-d H:i:s');
                    $dataFim = Carbon::now()->diffInHours( $end_date );

                    Log::info('Cron sessions finished --> Disconnected');

                    if($dataFim > 3 and $user->block_mail != 1) {
                        Notification::send($user, new AlertNotification([
                            'subject' => '📱Você não tem nenhuma sessão ativa',
                            'title' => 'Conecte seus dispositivos para gerenciar suas sessões',
                            'body' => 'Infelizmente você não tem nenhuma sessão ativa, ative agora mesmo e começe a enviar mensagens.',
                            'button' => 'Criar uma sessão',
                            'footer' => 'Conecte-se agora com o https://app.divulgawhats.com',
                            'actionURL' => url('/painel/sessions'),
                        ]));
                    }

                } catch (\Exception $e) {
                    Log::info(['Erro ao enviar e-mail de sessoes!']);
                }

            }

            try {

                if( $user->block_mail != 1 ){
                    Notification::send($user, new AlertNotification([
                        'subject' => '🔓 Aviso de novo login',
                        'title' => 'Notamos que seu e-mail '.$user->email.' foi utilizado para fazer login em nossa plataforma hoje com o IP '.self::getIp().' no dia '.Carbon::now()->format('d/m/Y H:i:s').$mensagem,
                        'body' => 'Esse é apenas um aviso de login, não se preocupe.',
                        'button' => 'Não foi você?',
                        'footer' => '',
                        'actionURL' => url('/painel/users/'.$user->id.'/edit'),
                    ]));
                }

            } catch (\Exception $e) {
                Log::info(['Erro ao enviar e-mail!']);
            }

            Log::info(['Usuáro logado na plataforma!', $user->username]);

            $user = User::whereId($request->user()->id);
            $user->update([ 'last_login' => Carbon::now() ]);


            $user = User::whereId($request->user()->id)->first();
            return $this->authenticated($request, $user);

        } catch (\Throwable $th) {
            Log::critical(['Falha no login', $th->getMessage()]);
            return redirect()->intended('/painel')->with('error', 'Falha durante o login, tente novamente mais tarde.'.$th->getMessage());
        }

    }

    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended('/painel')->with('success', 'Seja bem vindo(a) '.$user->username.'!');
    }
}
