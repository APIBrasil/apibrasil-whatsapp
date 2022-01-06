<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sessions;
use App\Models\Groups;
use App\Models\Contacts;
use App\Models\Messages;

class IntegracaoController extends Controller
{
    public function sendText(Request $request)
    {
        try {

            $this->validate($request, [
                'session_name' => 'required|string|max:18|min:12',
                'number' => 'required|max:18|min:12|string',
                'message' => 'required|max:900|min:1|string',
                'date_schedule_send' => 'nullable|date_format:Y-m-d H:i:s|after:now',
                'schedule' => 'nullable|string|min:3|max:3',
            ]);

            $session = Sessions::where('session_key', $request->session_name)
            ->where('user_id', auth()->user()->id)
            ->first();

            $grupo = Groups::where('group_id', $request->number)
            ->where('user_id', auth()->user()->id)
            ->first();

            $contato = Contacts::where('phone', $request->number)
            ->where('user_id', auth()->user()->id)
            ->first();

            if( isset($grupo) ){
                $contato_name = isset($grupo->name) ? $grupo->name : 'Grupo sem nome';

            }elseif( isset($contato)){
                $contato_name = isset($contato->name) ? $contato->name : 'Contato sem nome';
            }else{
                $contato_name = 'Contato sem nome';
            }

            $message = Messages::create([
                'name' => $contato_name,
                'number' => $request->number,
                'message' => isset($request->message) ? $request->message : '',
                'user_id' => $request->user()->id,
                'date_schedule_send' => isset($request->date_schedule_send) ? $request->date_schedule_send : null,
                'schedule' => isset($request->schedule) ? $request->schedule : 'false',
                'status' => 'Aguardando',
                'type' => 'Texto'
            ]);

            return response()->json(['error' => false, 'message' => $message, 'session' => $session], 200);

        } catch (\Throwable $th) {

            return response()->json(['error' => true, 'data' => $th->getMessage(), 'message' => 'Sessão não encontrada ou desconectada acesse: https://divulgawhatsapp.com.br/paine/sessions.'], 401);
        }
    }

    public function messages(Request $request)
    {
        try {

            $messages = Messages::where('user_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->limit($request->limit)
            ->get();

            return response()->json(['error' => false, 'data' => $messages], 200);

        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'data' => $th->getMessage()], 401);
        }
    }

    public function sessions(Request $request)
    {
        try {

            $sessions = Sessions::where('user_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->limit($request->limit)
            ->get();

            return response()->json(['error' => false, 'data' => $sessions], 200);

        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'data' => $th->getMessage()], 401);
        }
    }

}
