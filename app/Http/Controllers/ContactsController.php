<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use Illuminate\Http\Request;
use App\Models\Tags;
use App\Models\Groups;

use App\Models\Sessions;
use Yajra\DataTables\DataTables;

use App\Exports\ContactsExport;
use App\Imports\ContactsImport;
use Maatwebsite\Excel\Facades\Excel;
class ContactsController extends Controller
{

    public function index(Request $request)
    {

        $groups = Groups::orderBy('created_at', 'DESC')
        ->where('user_id', $request->user()->id)
        ->get();

        $tags = Tags::orderBy('created_at', 'DESC')
        ->where('user_id', $request->user()->id)
        ->get();

        $allTags = Tags::where('user_id', $request->user()->id)->get();

        $contatos = Contacts::orderBy('created_at', 'DESC')
        ->whereNotNull('phone')
        ->with('messagesContact')
        ->with('user')
        ->with('group')
        ->with('tag')
        ->where('user_id', $request->user()->id);

        $count_importeds = [
            'normal' => Contacts::where('user_id', $request->user()->id)->whereNotNull('phone')->where('imported', 0)->count() ?? 0,
            'importados' => Contacts::where('user_id', $request->user()->id)->whereNotNull('phone')->where('imported', 1)->count() ?? 0
        ];

        if($request->user()->can('contatos-ver-todos')){

            $groups = Groups::orderBy('created_at', 'DESC')->get();
            $tags = Tags::orderBy('created_at', 'DESC')->get();

            $contatos = Contacts::orderBy('created_at', 'DESC')
            ->whereNotNull('phone')
            ->with('messagesContact')
            ->with('user')
            ->with('group')
            ->with('tag');

            $count_importeds = [
                'normal' => Contacts::where('imported', 0)->whereNotNull('phone')->count() ?? 0,
                'importados' => Contacts::where('imported', 1)->whereNotNull('phone')->count() ?? 0
            ];

        }

        $sessions = Sessions::where('user_id', $request->user()->id)->get();

        return view('contacts.index')
        ->with('groups', $groups)
        ->with('tags', $tags)
        ->with('sessions', $sessions)
        ->with('allTags', $allTags)
        ->with('contatos', $contatos)
        ->with('count_importeds', $count_importeds);

    }

    public function datatables (Request $request)
    {

        try {

            $contatos = Contacts::select('id', 'name', 'created_at', 'phone', 'isBusiness', 'group_id', 'tag_id', 'user_id')
            ->orderBy('created_at', 'DESC')
            ->whereNotNull('phone')
            ->with('user')
            ->with('messagesContact')
            ->with('group')
            ->with('tag')
            ->where('imported', 0)
            ->where('user_id', $request->user()->id);

            if($request->user()->can('contatos-ver-todos')){

                $contatos = Contacts::select('id', 'name', 'created_at', 'phone', 'isBusiness', 'group_id', 'tag_id', 'user_id')
                    ->orderBy('created_at', 'DESC')
                    ->whereNotNull('phone')
                    ->with('messagesContact')
                    ->with('user')
                    ->with('group')
                    ->where('imported', 0)
                    ->with('tag');
            }

            return DataTables::of($contatos)->make(true);

        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'message' => $th->getMessage()]);
        }

    }

    public function datatablesImported (Request $request)
    {

        try {

            $contatos = Contacts::select('id', 'name', 'created_at', 'phone', 'isBusiness', 'group_id', 'tag_id', 'user_id')
            ->orderBy('created_at', 'DESC')
            ->whereNotNull('phone')
            ->with('messagesContact')
            ->with('user')
            ->with('group')
            ->with('tag')
            ->where('imported', 1)
            ->where('user_id', $request->user()->id);

            if($request->user()->can('contatos-ver-todos')){

             $contatos = Contacts::select('id', 'name', 'created_at', 'phone', 'isBusiness', 'group_id', 'tag_id', 'user_id')
                ->orderBy('created_at', 'DESC')
                ->whereNotNull('phone')
                ->with('messagesContact')
                ->with('user')
                ->with('group')
                ->with('tag')
                ->where('imported', 1);
            }

            return DataTables::of($contatos)->make(true);

        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'message' => $th->getMessage()]);
        }

    }

    public function store(Request $request)
    {
        try {

            $request->merge(['user_id' => $request->user()->id]);

            Contacts::create($request->all());
            return redirect()->route('contacts.index')->with('success', 'Contato cadastrado com sucesso.');

        } catch (\Throwable $th) {
            \Log::critical(['Falha no store contacts', $th->getMessage()]);
            throw $th;
        }
    }

    public function edit(Request $request, $id)
    {
        try {

            if($request->user()->can('contatos-ver-todos')){

                $groups = Groups::get();
                $tags = Tags::get();

                $contatos = Contacts::whereId($id)->firstOrFail();

            }else{

                $groups = Groups::where('user_id', $request->user()->id)->get();
                $tags = Tags::where('user_id', $request->user()->id)->get();

                $contatos = Contacts::whereId($id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            }

            return view('contacts.edit')
            ->with('groups', $groups)
            ->with('tags', $tags)
            ->with('contacts', $contatos);

        } catch (\Throwable $th) {
            \Log::critical(['Falha no edit contacts', $th->getMessage()]);
            throw $th;
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $contato = Contacts::whereId($id)->firstOrFail();
            $contato->update($request->all());

            $session = Sessions::where('user_id', $request->user()->id)->firstOrFail();
            $group = Groups::whereId($request->group_id)->firstOrFail();

            if ( isset($request->group_id) ){
                json_decode($this->requestIntegracao($request, $session, [
                    'session' => $session->session_name,
                    'number' => $contato->phone,
                    'groupid' => $group->group_id,
                ], 'addParticipant'), true, JSON_UNESCAPED_UNICODE);

            }

            return redirect()->route('contacts.index')->with('success', 'Contato atualizado com sucesso.');

        } catch (\Throwable $th) {
            \Log::critical(['Falha no update contacts', $th->getMessage()]);
            throw $th;
        }
    }

    public function export()
    {
        return Excel::download(new ContactsExport, 'users.xlsx');
    }

    public function import(Request $request)
    {
        try {

            $this->validate($request, [
                'file_import' => 'required|mimes:xls,xlsx'
            ]);

            Excel::import(new ContactsImport, $request->file('file_import'));

            return back()->withSuccess('Contatos importados com sucesso.');

        } catch (\Throwable $th) {

            return back()->withError('Falha ao importar contatos. / '.$th->getMessage());
        }

    }

    public function extracts(Request $request)
    {
        try {

            ini_set('max_execution_time', '90000'); //300 seconds = 5 minutes
            ini_set('memory_limit', '-1');

            $groups = Groups::where('user_id', $request->user()->id)
            ->get();

            $session = Sessions::where('user_id', $request->user()->id)->firstOrFail();

            foreach ($groups as $group) {

                $infos = self::requestIntegracao($request, $session, [
                    "session" => $session->session_name,
                    "groupid" => $group->group_id,
                ], 'getGroupMembers');

                $infos = json_decode($infos, true);
                $contacts = [];

                if(isset($infos['participants'])){
                    foreach ($infos['participants'] as $info) {

                        $getProfilePic = self::requestIntegracao($request, $session, [
                            "session" => $session->session_name,
                            "number" => $info['phone'],
                        ], 'getProfilePic');

                        $getProfilePic = json_decode($getProfilePic, true, JSON_UNESCAPED_UNICODE);

                        $avatar = isset($getProfilePic['pic_profile']['eurl']) ? file_get_contents($getProfilePic['pic_profile']['eurl']) : '';
                        $avatar = base64_encode($avatar);

                        $contacts = Contacts::create([
                            'avatar' => isset($avatar) ? $avatar : '',
                            'name' => isset($info['name']) ? $info['name'] : '',
                            'phone' => isset($info['phone']) ? $info['phone'] : '',
                            'isBusiness' => isset($info['isBusiness']) ? true : false,
                            'user_id' => $request->user()->id,
                            'group_id' => $group->id,
                        ]);

                        $contacts = Contacts::where('user_id', auth()->user()->id)->get();

                    }
                }

            };

            return response()->json(['contacts' => $contacts]);


        } catch (\Throwable $th) {
            \Log::critical(['Falha extracts', $th->getMessage()]);
            throw $th;
        }
    }

    public function show(Request $request, $phone)
    {
        try {

            $item = Contacts::where('phone', $phone)
            ->with('messagesContact')
            ->where('user_id', $request->user()->id)
            ->first();

            return response()->json($item, 200);

        } catch (\Throwable $th) {

            \Log::critical(['Falha showMessage', $th->getMessage()]);
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function destroyAll(Request $request, $situacao)
    {
        try {

            if($situacao == 'extract') {
                $contact = Contacts::where('user_id', $request->user()->id)
                ->where('imported', 0)->get();

                foreach($contact as $item){
                    $item->delete();
                }

            }else{
                $contact = Contacts::where('user_id', $request->user()->id)
                ->where('imported', 1)->get();

                foreach($contact as $item){
                    $item->delete();
                }
            }

            return response()->json(['success' => $contact->count().' contatos foram excluidos com sucesso.']);

        } catch (\Throwable $th) {
            \Log::critical(['Falha no delete contacts', $th->getMessage()]);
            throw $th;
        }

    }

    public function destroy($id)
    {
        try {

            $contact = Contacts::findOrFail($id);
            $contact->delete();

            return response()->json(['success' => $contact->count().' contatos foram excluidos com sucesso.']);

        } catch (\Throwable $th) {
            \Log::critical(['Falha no delete contacts', $th->getMessage()]);
            throw $th;
        }

    }
}
