<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use Illuminate\Http\Request;
use App\Models\Sessions;
use App\Models\Groups;
use App\Models\Messages;
use Yajra\DataTables\DataTables;
class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        try {

            $messages = Messages::orderBy('send_at', 'DESC')
            ->with('group')
            ->with('contact')
            ->where('user_id', $request->user()->id)
            ->paginate(10);

            $mensagensNaoEnviadas = Messages::where('status', 'Aguardando')
            ->where('user_id', $request->user()->id)
            ->count();

            $mensagensEnviadas = Messages::where('status', 'Enviado')
            ->where('user_id', $request->user()->id)
            ->count();

            if($request->user()->can('mensagens-todas')){
                $messages = Messages::orderBy('send_at', 'DESC')
                ->with('group')
                ->with('contact')
                ->paginate(10);

                $mensagensNaoEnviadas = Messages::where('status', 'Aguardando')
                ->count();

                $mensagensEnviadas = Messages::where('status', 'Enviado')
                ->count();

            }

            return view('messages.index')
            ->with('messages', $messages)
            ->with('mensagensNaoEnviadas', $mensagensNaoEnviadas)
            ->with('mensagensEnviadas', $mensagensEnviadas);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function datatables (Request $request)
    {
        try {

            ini_set('max_execution_time', '-1');
            ini_set('memory_limit', '-1');

            $messages = Messages::select('name',
            'id',
            'number',
            'message',
            'user_id',
            'status',
            'type',
            'send_at',
            'created_at',
            'schedule',
            'date_schedule_send')
            ->orderBy('send_at', 'DESC')
            ->with('user:id,name,username')
            ->with('group:id,name')
            ->with('contact:id,name,phone')
            ->where('user_id', $request->user()->id);

            if($request->user()->can('mensagens-todas')){
                $messages = Messages::select('id','name',
                'number',
                'message',
                'user_id',
                'status',
                'type',
                'created_at',
                'send_at',
                'schedule',
                'date_schedule_send')
                ->orderBy('send_at', 'DESC')
                ->with('user:id,name,username')
                ->with('group:id,name')
                ->with('contact:id,name,phone');
            }

            return DataTables::of($messages)->make(true);

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function sendText(Request $request)
    {
        try {

            switch ($request->again) {

                case "true":

                    $message = Messages::findOrFail($request->message_id);
                    $message->update([ 'status' => 'Aguardando' ]);
                    break;

                default:

                    if( isset($request->tag_id) ){

                            $itens = Contacts::where('user_id', $request->user_id)
                            ->where('tag_id', $request->tag_id)
                            ->get();

                            if( $itens->count() === 0 ) {
                                $itens = Groups::where('user_id', $request->user_id)
                                ->where('tag_id', $request->tag_id)
                                ->get();
                            };

                            $minutes = 0;
                            foreach ($itens as $item) {
                                $minutes++;

                                $message = Messages::create([
                                    'schedule' => $request->schedule ? $request->schedule : 'false',
                                    'date_schedule_send' => isset($request->date_schedule_send) ? \Carbon\Carbon::parse($request->date_schedule_send)->addMinutes($minutes)->format('Y-m-d H:i:s') : null,
                                    'name' => isset($item->name) ? $item->name : '',
                                    'number' => isset($item->group_id) ? $item->group_id : $item->phone,
                                    'message' => isset($request->message) ? $request->message : '',
                                    'user_id' => isset($request->user_id) ? $request->user_id : '',
                                    'status' => 'Aguardando'
                                ]);

                                if( !empty($request->file) ) {
                                    $message->update([ 'path' =>  $request->file, 'type' => 'Arquivo' ]);
                                }else{
                                    $message->update([ 'type' => 'Texto' ]);
                                }

                            }

                    }else{

                        $grupo = Groups::where('group_id', $request->number)->first();
                        $contato = Contacts::where('phone', $request->number)->first();

                        $contato_name = isset($contato->name) ? $contato->name : '';
                        $message = Messages::create([
                            'schedule' => $request->schedule,
                            'date_schedule_send' => isset($request->date_schedule_send) ? \Carbon\Carbon::parse($request->date_schedule_send)->addMinutes(5)->format('Y-m-d H:i:s') : null,
                            'name' => isset($grupo->name) ? $grupo->name : $contato_name,
                            'number' => isset($grupo->group_id) ? $grupo->group_id : $contato->phone,
                            'message' => isset($request->message) ? $request->message : '',
                            'user_id' => $request->user()->id,
                            'status' => 'Aguardando'
                        ]);

                        if( !empty($request->file) ) {
                            $message->update([ 'path' =>  $request->file, 'type' => 'Arquivo' ]);
                        }else{
                            $message->update([ 'type' => 'Texto' ]);
                        }

                    }

                    break;
            }

            if( !empty($request->file) ) {
                return response()->json(['success' => true, 'message' => 'Seu mensagem e arquivos foram enviados para uma fila, acompanhe o histórico de envios.' ], 200);
            }else{
                return response()->json(['success' => true, 'message' => 'Sua mensagem foi enviada para uma fila, acompanhe o histórico de envios.' ], 200);
            }

            return response()->json(['success' => false, 'message' => 'Não é possível enviar sua mensagem agora, inicie uma sessão e aguarde.' ], 400);

        } catch (\Throwable $th) {

            \Log::critical(['Falha sendText', $th->getMessage()]);
            return response()->json(['success' => false, 'message' => $th->getMessage() ], 400);

        }
    }

    public function show($id)
    {
        try {

            $message = Messages::findOrFail($id);
            return response()->json($message, 200);

        } catch (\Throwable $th) {

            \Log::critical(['Falha showMessage', $th->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {

            $message = Messages::findOrFail($id);
            $message->delete();

            return response()->json(['success' => true, 'message' => 'Mensagem excluída com sucesso.' ], 200);

        } catch (\Throwable $th) {
            \Log::critical(['Falha deleteMessage', $th->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Não é possível excluir sua mensagem agora, inicie uma sessão e aguarde.' ], 400);
        }
    }
}
