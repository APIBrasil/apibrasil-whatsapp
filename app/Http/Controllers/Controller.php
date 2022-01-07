<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use GuzzleHttp\Client;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function requestIntegracao($request, $session, $body = '', $action) {

        try {

            $client = new Client([ 'base_uri' => $session->server_whatsapp ]);

            $header = [
                'Content-Type' => 'application/json',
                "sessionkey" => $session->session_key ? $session->session_key : '',
            ];

            switch ($action) {
                case 'start':
                    $header['apitoken'] = $session->apitoken;
                    break;
            }

            \Log::notice([
                'header requestIntegracao', json_encode($header),
                'body requestIntegracao', json_encode($body)
            ]);

            $response = $client->post($action, [
                "verify" => false,
                'body' => json_encode($body),
                'headers' => $header,
            ]);

            $body = $response->getBody();

            \Log::notice(['callback requestIntegracao', $body]);

            return $body;

        } catch (\GuzzleHttp\Exception\ClientException $e) {

            \Log::critical(['Falha requestIntegracao', $e->getMessage()]);

            $response = $e->getResponse();
            return (string)($response->getBody());

        }

    }

    public static function getIp(){

        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){

            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }

        }
        return request()->ip(); // it will return server ip when no client ip found
    }

}
