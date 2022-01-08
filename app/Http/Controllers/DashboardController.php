<?php

namespace App\Http\Controllers;

use App\Models\Groups;

use App\Models\Messages;
use App\Models\Post;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request, $limit=5)
    {

        try{

            ini_set('max_execution_time', '900'); //300 seconds = 5 minutes
            ini_set('memory_limit', '-1');

            $msgs = Messages::where('user_id', $request->user()->id)->count();

            $aguardando = Messages::where('status', 'Aguardando');
            $enviado = Messages::where('status', 'Enviado');
            $users = User::withCount('messages')
            ->withCount('groups')
            ->withCount('sessions')
            ->orderBy('last_login', 'desc')
            ->limit($limit);

            if( $request->user()->can('dashboard-geral') ) {

                $msgs = Messages::count();

                $aguardando = $aguardando->where('status', 'Aguardando');
                $enviado = $enviado->where('status', 'Enviado');

                $groups = Groups::get();

                $users = $users->get();

            }else{

                $users = $users->whereId($request->user()->id)->get();

                $aguardando = $aguardando->where('user_id', $request->user()->id );
                $enviado = $enviado->where('user_id', $request->user()->id );

                $groups = Groups::where('user_id', $request->user()->id );
            }

            $chart1 = Messages::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"), \DB::raw("DAY(created_at) as day"))
                ->where('created_at', '>', Carbon::today()->subDay(6))
                ->where('status', 'Enviado')
                ->groupBy('day_name','day')
                ->orderBy('day');

                if( $request->user()->can('mensagens-todas')){
                    $chart1 = $chart1->get();
                }else{
                    $chart1 = $chart1->where('user_id', $request->user()->id)->get();
                }

                $data = [];
                foreach($chart1 as $row) {
                    $data['label'][] = $row->day_name;
                    $data['data'][] = (int) $row->count;
                }

            $chart_data = json_encode($data);

            $chart2 = Messages::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"), \DB::raw("DAY(created_at) as day"))
                ->where('created_at', '>', Carbon::today()->subDay(6))
                ->where('status', 'Aguardando')
                ->groupBy('day_name','day')
                ->orderBy('day');

                if( $request->user()->can('mensagens-todas')){
                    $chart2 = $chart2->get();
                }else{
                    $chart2 = $chart2->where('user_id', $request->user()->id)->get();
                }

                $data = [];
                foreach($chart2 as $row) {
                    $data['label'][] = $row->day_name;
                    $data['data'][] = (int) $row->count;
                }

            $chart_data2 = json_encode($data);

            $posts = Post::orderBy('created_at', 'desc')
            ->limit(3)->get();

            return view('dashboard', compact('posts', 'msgs', 'users', 'aguardando', 'groups', 'enviado', 'chart_data', 'chart_data2'));

        } catch (\Throwable $th) {

            \Log::critical($th->getMessage());
            return 'Ocorreu um erro inesperado '.$th->getMessage();
        }


    }
}
