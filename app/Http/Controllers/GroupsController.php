<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use App\Models\Groups;
use Illuminate\Http\Request;
use App\Models\Sessions;
use App\Models\User;
use App\Models\Contacts;
use Yajra\DataTables\DataTables;
class GroupsController extends Controller
{
/*
    public function __construct()
    {
        $sessions = Sessions::where('user_id', auth()->user()->id);
        $this->session = $sessions->first();
    }
 */
    public function index(Request $request)
    {


        try {

            $sessions = Sessions::where('user_id', $request->user()->id);
            $allTags = Tags::where('user_id', $request->user()->id)->get();

            $contacts = Contacts::orderBy('created_at', 'DESC')
            ->whereNotNull('phone')
            ->with('user')
            ->with('group')
            ->with('tag')
            ->where('user_id', $request->user()->id)->get();

            $groups = Groups::orderBy('created_at', 'DESC')
            ->with('tags')
            ->where('user_id', $request->user()->id);

            if( $request->user()->can('grupos-todos') ) {
                $allTags = Tags::get();

                $groups = Groups::orderBy('created_at', 'DESC')
                ->with('tags');

                $contacts = Contacts::orderBy('created_at', 'DESC')
                ->whereNotNull('phone')
                ->with('user')
                ->with('group')
                ->with('tag')
                ->get();

            }

            return view('groups.index')
            ->with('allTags', $allTags)
            ->with('contacts', $contacts)
            ->with('sessions', $sessions)
            ->with('groups', $groups);

        } catch (\Throwable $th) {
            \Log::critical(['Falha nos grupos', $th->getMessage()]);
        }
    }

    public function getImage(Request $request, $id)
    {

        try {

            $session = Sessions::where('user_id', $request->user()->id)->first();

            $image = json_decode($this->requestIntegracao($request, $session, [
                'session' => $session->session_name,
                'number' => $id,
            ], 'getProfilePic'), true, JSON_UNESCAPED_UNICODE);

            return response()->json(['success' => true, 'image' => $image]);

        } catch (\Throwable $th) {

            return response()->json(['error' => $th->getMessage()], 500);
        }

    }

    public function onlineShow(Request $request, $id)
    {
        try {

            $group = Groups::whereId($id)->first();
            $session = Sessions::where('user_id', $request->user()->id)->first();

            if(!isset($session)) {
                return response()->json(['message' => 'Sessão não encontrada'], 500);
            }

            $group = json_decode($this->requestIntegracao($request, $session, [
                'session' => $session->session_name ?? '',
                'inviteCode' => $group->link ?? '',
            ], 'getGroupInfoFromInviteLink'), true, JSON_UNESCAPED_UNICODE);

            $photo = json_decode($this->requestIntegracao($request, $session, [
                'session' => $session->session_name ?? '',
                'number' => isset($group['convite']['id']['user']) ? $group['convite']['id']['user'] : null,
            ], 'getProfilePic'), true, JSON_UNESCAPED_UNICODE);

            $group['outros'] = $photo;

            return response()->json($group);

        } catch (\Throwable $th) {

            \Log::critical(['Falha getInfos grupos', $th->getMessage()]);
            throw $th;

        }

    }

    public function show(Request $request)
    {
        try {

            ini_set('max_execution_time', '600'); //300 seconds = 5 minutes

            $group = Groups::with('messagesLight')
            ->where('group_id', $request->group_id)->first();

            $private = Contacts::with('messagesLight')
            ->where('phone', $request->phone)->first();

            return response()->json([
                'group' => $group,
                'private' => $private,
            ]);

        } catch (\Throwable $th) {

            \Log::critical(['Falha getInfos', $th->getMessage()]);
            throw $th;

        }

    }

    public function edit(Request $request, $id)
    {
        try {

            $group = Groups::whereId($id)
            ->where('user_id', $request->user()->id)
            ->whereNotNull('name')
            ->firstOrFail();

            $tags = Tags::where('user_id', $request->user()->id)
            ->get();

            $sessions = Sessions::where('user_id', $request->user()->id)
            ->get();

            return view('groups.edit')
            ->with('group', $group)
            ->with('sessions', $sessions)
            ->with('tags', $tags);

        } catch (\Throwable $th) {

            \Log::critical(['Falha editar grupo', $th->getMessage()]);
            return redirect()->back()->with('error', 'Falha ao editar grupo');

        }

    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ]);

            $session = Sessions::where('user_id', $request->user()->id)->firstOrFail();

            $createGroup = json_decode($this->requestIntegracao($request, $session, [
                'session' => $session->session_name,
                'name' => $request->name,
                'participants' => isset($request->contacts) ? implode(',', $request->contacts) : '',
            ], 'createGroup'), true, JSON_UNESCAPED_UNICODE);

            if(isset($createGroup['participants']) and isset($createGroup['id'])){

                json_decode($this->requestIntegracao($request, $session, [
                    'session' => $session->session_name,
                    'number' => isset($request->contacts[0]) ? $request->contacts[0]->phone : '',
                    'groupid' => $createGroup['id'],
                ], 'promoteParticipant'), true, JSON_UNESCAPED_UNICODE);

                json_decode($this->requestIntegracao($request, $session, [
                    'session' => $session->session_name,
                    'groupid' => $createGroup['id'],
                    'description' => $request->description,
                ], 'setGroupDescription'), true, JSON_UNESCAPED_UNICODE);

                $getLink = json_decode($this->requestIntegracao($request, $session, [
                    'session' => $session->session_name,
                    'groupid' => $createGroup['id'],
                    'description' => $request->description,
                ], 'getGroupInviteLink'), true, JSON_UNESCAPED_UNICODE);

                if( isset($getLink['convite']) ){

                    $info = json_decode($this->requestIntegracao($request, $session, [
                        'session' => $session->session_name,
                        'groupid' => $createGroup['id'],
                        'inviteCode' => $getLink['convite'],
                    ], 'getGroupInfoFromInviteLink'), true, JSON_UNESCAPED_UNICODE);

                    $group = new Groups();
                    $group->name = $request->name;
                    $group->link = $getLink['convite'] ?? '';
                    $group->size = $info['size'] ?? 0;
                    $group->creation = $info['convite']['creation'] ?? '';
                    $group->owner = $info['convite']['owner']['user'] ?? '';
                    $group->restrict = $info['convite']['restrict'] ?? '';

                    $group->desc = $info['convite']['desc'];
                    $group->subject = $info['convite']['subject'];

                    $group->user_id = $request->user()->id;
                    $group->group_id = $createGroup['id'];

                    $group->save();

                }

                return redirect()->route('groups.index')->with('success', 'O grupo '.$request->name.' criado com sucesso com '.count($createGroup['participants']).' contatos');
            };

            return redirect()->route('groups.index')->with('error', 'Erro ao criar grupo');

        } catch (\Throwable $th) {

            \Log::info(['falha ao criar grupo', $th->getMessage()]);
            return redirect()->back()->withError('error', 'Falha ao criar grupo');
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $session = Sessions::where('user_id', $request->user()->id)->firstOrFail();
            $group = Groups::whereId($id)->where('user_id', $request->user()->id)->firstOrFail();

            json_decode($this->requestIntegracao($request, $session, [
                'session' => $session->session_name,
                'groupid' => $group->group_id,
                'description' => $request->desc,
            ], 'setGroupDescription'), true, JSON_UNESCAPED_UNICODE);

            json_decode($this->requestIntegracao($request, $session, [
                'session' => $session->session_name,
                'groupid' => $group->group_id,
                'title' => $request->name,
            ], 'setGroupSubject'), true, JSON_UNESCAPED_UNICODE);

            /* $nameFile = null;
            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {

                $name = uniqid(date('HisYmd'));
                $extension = $request->avatar->extension();
                $nameFile = "{$name}.{$extension}";
                $upload = $request->avatar->storeAs('fotosgrupos', $nameFile);

                if ( isset($upload) ){
                    $url = env('APP_URL').$upload;
                    $pic = json_decode($this->requestIntegracao($request, $session, [
                        'session' => $session->session_name,
                        'path' => $url,
                        'groupid' => $group->group_id,
                    ], 'setGroupPic'), true, JSON_UNESCAPED_UNICODE);
                }

            } */

            $group->name = $request->name;
            $group->subject = $request->subject;
            $group->desc = $request->desc;
            $group->tag_id = $request->tag_id ?? null;
            $group->save();

            return redirect()->route('groups.index')->with('success', 'As informações do grupo foram atualizadas com sucesso');

        } catch (\Throwable $th) {

            \Log::info(['falha ao editar grupo', $th->getMessage()]);
            return redirect()->back()->with('error', 'Falha ao editar grupo /'.$th->getMessage());
        }
    }

    public function getAllGroups(Request $request)
    {

        try {

            ini_set('max_execution_time', '90000'); //300 seconds = 5 minutes
            ini_set('memory_limit', '-1');

            $session = Sessions::where('user_id', $request->user()->id)->first();

            $payload = json_decode($this->requestIntegracao($request, $session, [
                'session' => $session->session_name,
            ], 'getAllGroups'), true, JSON_UNESCAPED_UNICODE);

            if( isset($payload['status']) and $payload['status'] == 'Disconnected'){
                return response()->json([
                    'group' => [],
                    'message' => 'Sessão desconectada',
                ], 500);
            }

            if( isset($payload['groups']) ){
                foreach ($payload['groups'] as $group) {

                    $grupo = Groups::updateOrCreate([
                        'name' => $group->name ?? '',
                        "user_id" => $request->user()->id,
                        'group_id' => $group['id'],
                    ]);

                    $invite = self::requestIntegracao($request, $session, [
                        "session" => $session->session_name,
                        "groupid" => $group['id'],
                    ], 'getGroupInviteLink');

                    $invite = json_decode($invite);

                    if( !isset($invite->status) ) {

                        $infos = self::requestIntegracao($request, $session, [
                            "session" => $session->session_name,
                            "inviteCode" => $invite->convite,
                        ], 'getGroupInfoFromInviteLink');

                        $photo = self::requestIntegracao($request, $session, [
                            "session" => $session->session_name,
                            "number" => $group['id'],
                        ], 'getProfilePic');

                        $photo = json_decode($photo);

                        $avatar = isset($photo->pic_profile->eurl) ? file_get_contents($photo->pic_profile->eurl) : '';
                        $photo = base64_encode($avatar);

                        $info = json_decode($infos);
                        $info = $info->convite ?? [];

                        $grupo->update([
                            "description" => $info->description ?? '',
                            "avatar" => $info->avatar ?? '',
                            "link" => isset($invite->convite) ? $invite->convite : '',
                            "size" => $info->size ?? 0,
                            "owner" => $info->owner->user ?? '',
                            "subject" => $info->subject ?? '',
                            "creation" => $info->creation ?? '',
                            "subject_time" => $info->subject_time ?? '',
                            "subject_owner" => $info->subject_owner->user ?? '',
                            "restrict" => $info->restrict ?? '',
                            "ephemeral_duration" => $info->ephemeralDuration ?? '',
                            "desc" => $info->desc ?? '',
                            "desc_id" => $info->desc_id ?? '',
                            "desc_time" => $info->desc_time ?? '',
                            "desc_owner" => $info->desc_owner->user ?? '',
                        ]);
                    }
                }

                return response()->json(['groups' => $payload['groups'], 'message' => 'Grupos importados com sucesso!'], 200);
            }

            return response()->json(['groups' => [], 'message' => 'Não foi possível obter os grupos, tente novamente mais tarde.'], 400);

        } catch (\Throwable $th) {

            \Log::critical(['Falha no getAllGroups', $th->getMessage()]);

            return response()->json([
                'group' => [],
                'message' => $th->getMessage(),
            ], 500);
        }

    }

    public function datatables (Request $request)
    {

        try {

            $groups = Groups::orderBy('created_at', 'DESC')
            ->with('tags')
            ->with('user')
            ->with('messagesGroups')
            ->where('user_id', $request->user()->id);

            if( $request->user()->can('grupos-todos') ) {
                $groups = Groups::orderBy('created_at', 'DESC')
                ->with('user')
                ->with('messagesGroups')
                ->with('tags');
            }

            return DataTables::of($groups)->make(true);

        } catch (\Throwable $th) {

            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
            ], 500);
        }

    }

    public function deleteAll(Request $request)
    {
        try {

            $itens = Groups::where('user_id', $request->user()->id)->get();

            foreach ($itens as $item) {
                $itens = Groups::whereId($item->id);
                $item->delete();
            }

            return response()->json(['error' => false, 'message'=>'Os grupos foram deletados com sucesso.']);

        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'message'=>'Erro ao deletar grupos!']);

        }
    }

    public function destroy($id)
    {
        try {

            $item = Groups::findOrFail($id);
            $item->delete();

            return response()->json(['error' => false, 'message'=>'O grupo foi deletado com sucesso.']);

        } catch (\Throwable $th) {

            return response()->json(['error' => true, 'message'=>'Erro ao deletar grupo!']);

        }
    }
}
