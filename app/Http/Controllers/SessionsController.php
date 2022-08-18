<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sessions;
use DataTables;
use Carbon\Carbon;
use Log;
use Auth;
//use \Yajra\DataTables\Facades\DataTables;
class SessionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $data = [
            "server_host" => "https://whatsapp2.contrateumdev.com.br", //required
            "method" => "POST", //optional
            "apitoken" => "YOUR_TOKEN_API", //required
            "session_name" => "YOUR_SESSION_NAME", //required
            "session_key" => "YOUR_SESSION_KEY", //required
            "wh_status" => "", //optional
            "wh_message" => "", //optional
            "wh_connect" => "", //optional
            "wh_qrcode" => "", //optional
        ];

        try {

            if($request->user()->can('sessoes-todas')) {

                $sessions = Sessions::orderBy('created_at', 'DESC')
                ->with('user')
                ->paginate(10);

            }else{

                $sessions = Sessions::orderBy('created_at', 'DESC')
                ->with('user')->where('user_id', $request->user()->id)
                ->paginate(10);

            }

            return view('sessions.index')->with('sessions', $sessions);

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function datatables (Request $request)
    {

        try {

            if($request->user()->can('sessoes-todas')) {

                $sessions = Sessions::orderBy('created_at', 'DESC')
                ->with('user')
                ->get();

            }else{

                $sessions = Sessions::orderBy('created_at', 'DESC')
                ->with('user')->where('user_id', $request->user()->id)
                ->get();

            }

            return DataTables::of($sessions)->make(true);

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function create(Request $request)
    {

        try {

            $sessions = Sessions::where('user_id', $request->user()->id)->count();

            if($sessions >= Auth::user()->roles()->first()->qt_devices){
                return redirect()->route('sessions.index')->with('error', 'Limite de dispositivos atingido, compre mais slots para continuar usando o WhatsApp. contato@apigratis.com.br');
            }

            $sessions = Sessions::get();
            return view('sessions.create', compact('sessions'));

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function store(Request $request)
    {
        try {

            $request->merge(['user_id' => $request->user()->id]);

            Sessions::create($request->all());
            return redirect()->route('sessions.index')->with('success','Session created successfully');

        } catch (\Throwable $th) {

            \Log::critical(['Erro ao criar sessão', $th->getMessage()]);
            return redirect()->route('sessions.index')
            ->with('error', $th->getMessage());

        }

    }

    public function start(Request $request)
    {
        try {

            $session = Sessions::where('session_name', $request->session_name)
            ->where('session_key', $request->session_key)
            ->where('user_id', $request->user()->id)
            ->first();

            $start = json_decode(self::requestIntegracao($request, $session, [
                "session" => $session->session_name,
                "wh_connect" => $session->webhook_wh_connect ?? '',
                "wh_qrcode" => $session->webhook_qr_code ?? '',
                "wh_status" => $session->webhook_wh_status ?? '',
                "wh_message" => $session->webhook_wh_message ?? ''
            ], 'start'), true, JSON_UNESCAPED_UNICODE);

            if(isset($start['result']) and $start['result'] == 401){
                return response()->json($start, 401);
            }

            if( isset($start['result']) and $start['result'] != 401){
                $update = Sessions::find($session->id);
                $update->update([
                    'status' => 'CONNECTED'
                ]);
            };

            return response()->json([
                'error' => false,
                'message' => 'Sua sessão foi iniciada com sucesso, aguarde.',
                //'checksession' => $checkSession,
                'sessions' => $session
            ], 200);

        } catch (\Throwable $th) {
            \Log::critical(['Erro ao iniciar sessão', $th->getMessage()]);
            return response()->json(['error' => true, 'message' => $th->getMessage()], 500);
        }

    }

    public function onlineShow(Request $request, $id)
    {
        try {

            $session = Sessions::whereId($id)
            ->where('user_id', $request->user()->id)
            ->first();

            if($request->user()->can('sessoes-todas')) {
                $session = Sessions::whereId($id)
                ->first();
            }


            if(!isset($session)) {
                return response()->json(['message' => 'Sessão não encontrada'], 500);
            }

            $host = json_decode($this->requestIntegracao($request, $session, [
                'session' => $session->session_name ?? '',
            ], 'getWid'), true, JSON_UNESCAPED_UNICODE);

            $ip = self::getIp() !== null ? self::getIp() : null;
            $location = json_encode(geoip($ip));

            $session->update([
                'location' => $location ?? '',
                'ip_host' => self::getIp() ?? '',
                'last_connected' => Carbon::now(),
                'connected' => $host['status'] ?? '',
                'result' => $host['result'] ?? ''
            ]);

            return response()->json(['host' => $host, 'session' => $session], 200);

        } catch (\Throwable $th) {

            \Log::critical(['Falha getInfos session', $th->getMessage()]);
            throw $th;

        }

    }

    public function edit($id)
    {
        try {

            $session = Sessions::findOrFail($id);
            return view('sessions.edit')->with('session', $session);

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function update(Request $request, $id)
    {
        try {

            $this->validate($request, [
                'session_name' => 'required',
                'session_key' => 'required',
            ]);

            $session = Sessions::findOrFail($id);
            $request->merge(['user_id' => $request->user()->id]);

            $session->update($request->all());

            return redirect()->route('sessions.index')
                            ->withSuccess('success','A sessão foi atualizada com sucesso.');
        } catch (\Throwable $th) {
            return redirect()->route('sessions.index')
                        ->withErrors('Problema ao atualizar a sessão!');
        }

    }

    public function stop(Request $request, $id)
    {
        try {

            $session = Sessions::where('user_id', $request->user()->id)
            ->whereId($id)->first();

            if(isset($session->session_name)){

                $close = json_decode(self::requestIntegracao($request, $session, [
                    "session" => $session->session_name,
                ], 'close'));

                self::requestIntegracao($request, $session, [
                    "session" => $session->session_name,
                ], 'deleteSession');

                $update = Sessions::find($session->id);
                $update->update([
                    'status' => 'DESCONECTADA'
                ]);

                return response()->json(['success' => true, 'message' => 'Sessão DISCONECTED success!' ?? '']);
            }

            $update = Sessions::find($session->id);
            $update->update([
                'status' => 'DESCONECTADA'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sessão não existe'
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }


    }

    public function destroy(Request $request, $id)
    {
        try {

            $session = Sessions::findOrFail($id);

            try {
                self::requestIntegracao($request, $session, [
                    "session" => $session->session_name,
                ], 'close');

                self::requestIntegracao($request, $session, [
                    "session" => $session->session_name,
                ], 'deleteSession');
            } catch (\Throwable $th) {
                Log::error(['Falha ao deletar sessão', $th->getMessage()]);
            }

            $session->delete();

            return redirect()->route('sessions.index')
                        ->with('success', 'Sessão deletada com sucesso!');

        } catch (\Throwable $th) {

            return redirect()->route('sessions.index')
                        ->with('error', 'Problema ao deletar a sessão!'. $th->getMessage());

        }
    }
}
