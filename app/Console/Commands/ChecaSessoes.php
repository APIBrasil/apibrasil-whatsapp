<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Log;
use App\Models\User;
use App\Models\Sessions;
use Carbon\Carbon;
use GuzzleHttp\Client;

use Notification;
use App\Notifications\AlertNotification;

class ChecaSessoes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        try {

            $users = User::with('sessions')
            ->orderBy('last_login', 'desc')
            ->where('last_login', '<', Carbon::now()->subMinutes(60))
            ->get();

            foreach ($users as $user)
            {

                foreach ($user->sessions as $session) {

                    echo "Começando:::: {$user->name}\n";
                    echo "Sessão:::: $session->session_name \n";

                    Log::info('Cron sessions start --> '.$session->name);

                    $getHostDevice = json_decode($this->requestIntegracao($user, $session, [
                        'session' => $session->session_name,
                    ], 'getHostDevice'), true, JSON_UNESCAPED_UNICODE);

                    var_dump("Sessão::::", $getHostDevice);

                    Log::notice([
                        'Cron sessions checked' => $getHostDevice,
                        'session_name' => $session->session_name
                    ]);

                    if( isset($getHostDevice) and isset($getHostDevice['result']) )
                    {
                        if( intVal($getHostDevice['result']) == 200 ){
                            echo "Status:::: {$session->name} CONNECTED\n";

                            $update = Sessions::find($session->id);
                            $update->update([
                                'status' => 'CONNECTED',
                            ]);
                        }

                    }

                    if(isset($getHostDevice) and isset($getHostDevice['result']) ){

                        if( intVal($getHostDevice['result']) == 401){

                            $update = Sessions::where('user_id', $user->id)
                            ->where('session_name', $session->session_name);

                            echo "Status:::: {$session->name} DISCONNECTED\n";

                            $update->update([
                                'last_activity' => Carbon::now(),
                                'status' => 'DISCONNECTED',
                            ]);

                            $end_date = isset($user->last_activity) ? $user->last_activity : Carbon::now()->format('Y-m-d H:i:s');
                            $dataFim = Carbon::now()->diffInHours( $end_date );

                            Log::info('Cron sessions finished --> Disconnected');

                            if($dataFim > 3 and $user->block_mail != 1) {
                                Notification::send($user, new AlertNotification([
                                    'subject' => 'Sessão do whatsapp: DISCONNECTED',
                                    'title' => 'Sua ultima atividade foi '.$dataFim.' horas atrás e sua sessão se encontra com a situação DISCONNECTED',
                                    'body' => 'Esse serviço verifica a situação da sua sessão do WhatsApp a cada 1 minuto, para que você nunca fique sem enviar mensagens, à última verificação foi à '.Carbon::now()->diffForHumans().' atràs.',
                                    'button' => 'Minhas sessões',
                                    'footer' => 'Esse aviso é automático',
                                    'actionURL' => url('/painel/message'),
                                ]));
                            }
                        }

                    }else{

                        $update = Sessions::where('user_id', $user->id)
                        ->where('session_name', $session->session_name);

                        $update->update([
                            'last_activity' => Carbon::now(),
                            'status' => 'DESCONECTADO'
                        ]);
                    }

                    \Log::notice(['Callback do start', $getHostDevice]);
                    Log::info('Cron sessions finished --> '.$session->session_name);

                    echo "Fim, esperando:::: 5 segundos\n";
                    sleep(5);
                }

            };

        } catch (\Throwable $th) {

            throw $th;

        }

    }

    public function requestIntegracao($user, $session, $body, $action)
    {

        try {

            $host = isset($user->server_whatsapp) ? $user->server_whatsapp : false;
            $client = new Client([ 'base_uri' => $host ]);

            $header = [
                'Content-Type' => 'application/json',
                "sessionkey" => $session->session_key ?? '',
            ];

            $response = $client->post($action, [
                "verify" => false,
                'body' => json_encode($body),
                'headers' => $header,
            ]);

            $body = $response->getBody();

            return $body;


        } catch (\GuzzleHttp\Exception\ClientException $e) {

            $response = $e->getResponse();
            return (string)($response->getBody());

        }

    }
}
