@extends('layouts.app-master')

@section('content')
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

@include('layouts.partials.navbar')

<div class="page-wrapper">

    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="page-title mb-0 p-0">Minhas sessões</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/painel">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('sessions.index') }}>"> Sessões </a> </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-md-6 col-4 align-self-center">
                @if( count($sessions) <= Auth::user()->roles()->first()->qt_devices)
                <div class="text-end upgrade-btn">
                    @can('sessoes-iniciar')<a href="{{ route('sessions.create') }}" class="btn btn-success d-none d-md-inline-block text-white" target="_self"> <i class="fab fa-whatsapp"></i> Criar uma sessão</a>@endcan
                </div>
                @endif

            </div>
        </div>
    </div>

    <div class="container-fluid">

        <div class="card row">

            <div class="mt-2">
                @include('layouts.partials.messages')

                <div class="alert alert-warning" role="alert">
                    O <strong>status da sessão</strong> e <strong>situação da sessão</strong> corresponde a data: {{ \Carbon\Carbon::now()->subMinutes(1)->format('d/m/Y H:i:s') }}
                </div>

            </div>

            @can('sessoes-visualizar')
            <table class="table table-striped" id="table_id">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Aparelho</th>
                        <th>Criada</th>
                        <th>Ultima atividade</th>
                        <th>Responsável</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sessions as $key => $item)
                    <tr>
                        <td>{{ $item->session_name }}</td>
                        @if($item->status == 'CONNECTED')
                        <td><a href="#" onclick="showInfo('{{$item->id}}');"> <i class="fas fa-mobile text-success"></i> {{ $item->session_key }} </a> </td>
                        @else
                        <td><a href="#" onclick="showInfo('{{$item->id}}');"> <i class="fas fa-mobile text-danger"></i> {{ $item->session_key }} </a></td>
                        @endif

                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/y H:i:s') }}</td>

                        <td>{{ \Carbon\Carbon::parse($item->last_activity)->format('d/m/y H:i:s') }}</td>
                        <td>{{ $item->user->username }}</td>

                        <td> <span class="badge bg-primary"> {{ $item->status ?? 'Indisponível' }} </span> </td>

                        <td>

                            @can('sessoes-iniciar')

                            @if($item->status == 'CONNECTED')
                            <a class="btn btn-sm btn-success" id="btnOpenSession" onclick="stopSession('{{ $item->id }}');"> <i class="fas fa-stop"></i> </a>
                            @elseif( $item->status == 'STARTING')
                            <a class="btn btn-sm btn-warning" id="btnOpenSession" title="Iniciando a sessão, aguarde"> <i class="fas fa-clock"></i> </a>
                            @else
                            <a class="btn btn-sm btn-success" id="btnOpenSession" data-session="{{$item}}" onclick="openModalQr('{{ $item->server_whatsapp }}', '{{ $item->session_name }}','{{ $item->session_key }}')"> <i class="fas fa-play"></i> </a>
                            <a class="btn btn-primary btn-sm" href="{{ route('sessions.edit', $item->id) }}"> <i class="fas fa-edit"></i> </a>
                            @endif

                            @endcan

                            @can('sessoes-deletar')
                            {!! Form::open(['method' => 'DELETE','route' => ['sessions.destroy', $item->id],'style'=>'display:inline']) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-cloud"></i> Você não permissão para visualizar isso, solicite em contato@apigratis.com.br.
            </div>
            @endcan

            {!! $sessions->links() !!}
        </div>

    </div>

    @include('layouts.partials.footer')

</div>

<!-- Modal -->
<div class="modal fade" id="modalQR" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div> -->
            <div class="modal-body text-center">
                <img src="" id="imageQr" class="img-fluid">
                <h4 class="text-success mt-3" id="textSession"> </h4>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-secondary" onclick="window.location.reload()">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnStartSession"> <i class="fas fa-play"></i> Clique para iniciar a sessão</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalShowSession" tabindex="-1" aria-labelledby="modalShowSessionLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-2">
                        <img id="imgModelo" src="https://media.istockphoto.com/vectors/not-connected-signal-wifi-sign-vector-vector-id1072435934?k=20&m=1072435934&s=612x612&w=0&h=E0K57eXIuflKGuieqApRQ6W-DPENTg-0x6IlxalpNkI=" width="100%" />
                    </div>
                    <div class="col-md-10">

                        <div class="row">

                            <h4> Dados da API e sessão </h4>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="server_whatsapp" class="form-label">Servidor MyZAP 2.0</label>
                                    <input class="form-control" name="server_whatsapp" disabled />
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="apitoken" class="form-label">API Token</label>
                                    <input class="form-control" name="apitoken" disabled />
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="session_key" class="form-label">Session Key</label>
                                    <input class="form-control" name="session_key" disabled />
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="session_name" class="form-label">Session Name</label>
                                    <input class="form-control" name="session_name" disabled />
                                </div>
                            </div>
                            <hr />
                        </div>

                        <div class="row">

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="connected" class="form-label">Connectado</label>
                                    <input class="form-control" name="connected" disabled />
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="locales" class="form-label">Locale</label>
                                    <input class="form-control" name="locales" disabled />
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="number" class="form-label">Número</label>
                                    <input class="form-control" name="number" disabled />
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="device_manufacturer" class="form-label">Fabricante</label>
                                    <input class="form-control" name="device_manufacturer" disabled />
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="device_model" class="form-label">Modelo</label>
                                    <input class="form-control" name="device_model" disabled />
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="mcc" class="form-label">MCC</label>
                                    <input class="form-control" name="mcc" disabled />
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="row">

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="mnc" class="form-label">MNC</label>
                                    <input class="form-control" name="mnc" disabled />
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="os_build_number" class="form-label">OS Build number</label>
                                    <input class="form-control" name="os_build_number" disabled />
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="os_version" class="form-label">OS Version</label>
                                    <input class="form-control" name="os_version" disabled />
                                </div>
                            </div>

                        </div>

                        <hr />

                        <div class="row">

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="wa_version" class="form-label">Versão WhatsApp</label>
                                    <input class="form-control" name="wa_version" disabled />
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="pushname" class="form-label">Push name</label>
                                    <input class="form-control" name="pushname" disabled />
                                </div>
                            </div>

                        </div>

                    </div>
                </div>


            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
    $(function() {

        table = $('#table_id').DataTable({
            select: true,
            responsive: true,
            "order": [
                [3, "desc"]
            ],
        });

        $('#table_id tbody').on('click', 'tr', function() {
            $(this).toggleClass('selected');
            var pos = table.row(this).index();
            var row = table.row(pos).data();
        });

        $('#modalQR').modal({
            backdrop: 'static'
        })

        document.getElementById('imageQr').src = "https://cdn.dribbble.com/users/1187836/screenshots/6012802/13-qrcode.gif"
    });

    async function openModalQr(host, sessionName, sessionKey) {

        console.log(host, sessionName, sessionKey)
        socket = io(host);

        socket.on(`events`, (events) => {
            $(".session_state").html(`<span title="Situação da Sessão"> <i class="fas fa-mobile"></i> ${events?.state || 'Desconectado'} </span>`)
            console.log(events)
        })

        socket.on(`send-message`, (message) => {
            $(".session_state").html(`<span title="Status da última mensagem"> <i class="far fa-envelope"></i> ${message?.status || ''} </span>`)
            console.log(message)
        })

        setTimeout(() => {
            $(".session_state").html(``)
        }, 10000)

        data = $("#btnOpenSession").data("session")

        wh_connect = data?.webhook_wh_connect,
            wh_status = data?.webhook_wh_status,
            wh_message = data?.webhook_wh_message,
            wh_qrcode = data?.webhook_wh_qrcode,

            $("#modalQR").modal('show');
        $(".modal-title").html('Conectar-se com o WhatsApp')
        $("#btnStartSession").attr('onclick', `iniciarSessao('${sessionName}','${sessionKey}')`)

        socket.on(`events`, (sessao) => {

            $("#textSession").html(sessao?.message);
            $('.modal-body').LoadingOverlay('hide');

            if (sessionName == sessao.session) {

                switch (sessao?.state) {

                    case "DISCONNECTED":

                        Swal.fire({
                            class: 'error',
                            icon: 'error',
                            title: `${sessao?.state}`,
                            text: `${sessao?.message}`,
                            footer: '<a href="https://wa.me/5531995360492" target="_blank">Precisa de ajuda?</a>'
                        }).then((result) => {
                            window.location.reload();
                        })

                        $("#btnStartSession").attr('disabled', false);
                        $("#btnStartSession").html('Reconectar!');

                        break;

                    case "STARTING":

                        document.getElementById('imageQr').src = "https://dsw.conjuntaresmodule.pw/wp-content/themes/prime-hosting/images/loader.gif";
                        $("#btnStartSession").attr('disabled', true);
                        $("#btnStartSession").html('Aguardando...');

                        break;

                    case "QRCODE":

                        document.getElementById('imageQr').src = sessao?.qrCode;
                        $("#btnStartSession").attr('disabled', false);
                        $("#btnStartSession").html('Aguardando...');

                        break;

                    case "qrReadSuccess":

                        $("#btnStartSession").html('QRCODE pronto...');
                        $("#btnStartSession").attr('disabled', false);

                        break;

                    case "CONNECTED":

                        Swal.fire({
                            class: 'success',
                            icon: 'success',
                            title: `${data?.state ?? 'Conectado'}`,
                            text: `${data?.message ?? 'Sessão conectada com sucesso.'}`,
                        }).then(() => {
                            window.location.reload();
                        })

                        $("#btnStartSession").html('Conectado!');
                        $("#btnStartSession").attr('disabled', false);
                        $('#modalQR').modal('hide');

                        break;
                }
            }

        })

    }

    async function iniciarSessao(sessionName, sessionKey) {

        await $.post({
            url: `/painel/sessions/start`,
            method: `POST`,
            data: {
                "_token": "{{ csrf_token() }}",
                "session_key": `${sessionKey}`,
                "session_name": `${sessionName}`,
                "wh_status": wh_status,
                "wh_message": wh_message,
                "wh_qrcode": wh_qrcode,
                "wh_connect": wh_connect
            },
            beforeSend: function(data) {
                document.getElementById('imageQr').src = "https://dsw.conjuntaresmodule.pw/wp-content/themes/prime-hosting/images/loader.gif";
                $("#btnStartSession").attr('disabled', true);
                $("#btnStartSession").html('Iniciando uma sessão');
                $('.modal-body').LoadingOverlay('show');
            },
            success: function(data) {

                //data = typeof(data) != 'undefined' ? JSON.parse(data) : false;
                if (data?.checksession?.connected == true) {
                    Swal.fire({
                        class: 'success',
                        icon: 'success',
                        title: `${data?.checksession?.pushname}`,
                        text: `${data?.message}  ${data?.checksession?.phone?.device_manufacturer  ? `dispositivo ${data?.checksession?.phone?.device_manufacturer}` : ''}`,
                    }).then((value) => {
                        window.location.reload();
                    })

                    $('#modalQR').modal('hide');
                }

                $('.modal-body').LoadingOverlay('hide');
            },
            error: function(error) {

                Swal.fire({
                    class: 'error',
                    icon: 'error',
                    title: `${error?.responseJSON?.reason ?? 'Erro desconhecido ao iniciar!'}`,
                    text: `${error?.responseJSON?.message ?? 'Verifique o token da sua API e dados da sessão.'}`,
                    footer: '<a href="https://wa.me/5531995360492" target="_blank">Precisa de ajuda?</a>'
                }).then((value) => {
                    window.location.reload();
                })

                $('.modal-body').LoadingOverlay('hide');
            },
        });

    }

    async function showInfo(id) {

        await $.post({
            url: `/painel/sessions/online/${id}/show`,
            method: `POST`,
            data: {
                "_token": "{{ csrf_token() }}",
                "session_id": `${id}`,
            },
            beforeSend: function(data) {
                $.LoadingOverlay('show');
            },
            success: function(data) {

                if (data?.result === 401) {
                    Swal.fire({
                        class: 'error',
                        icon: 'error',
                        title: `Ops!`,
                        text: `${data?.messages}`,
                    })
                    $.LoadingOverlay('hide');
                    return false;
                }

                $(".modal-title").html(`Visualizar grupo`);
                $('#modalShowSession').modal('show');

                $("input[name='server_whatsapp']").val(`https://${ window.location.href.split('/')[2] }/api/myzap`);
                $("input[name='apitoken']").val(`${data?.session?.apitoken ?? 'Indispoível'}`);
                $("input[name='session_key']").val(`${data?.session?.session_key ?? 'Indispoível'}`);
                $("input[name='session_name']").val(`${data?.session?.session_name ?? 'Indispoível'}`);

                $("input[name='connected']").val(`${data?.host?.connected ?? 'Indispoível'}`);
                $("input[name='locales']").val(`${data?.host?.apitoken ?? 'Indispoível'}`);
                $("input[name='number']").val(`${data?.host?.number ?? 'Indispoível'}`);
                $("input[name='device_manufacturer']").val(`${data?.host?.phone?.device_manufacturer ?? 'Indispoível'}`);
                $("input[name='device_model']").val(`${data?.host?.phone?.device_model ?? 'Indispoível'}`);
                $("input[name='mcc']").val(`${data?.host?.phone?.mcc ?? 'Indispoível'}`);
                $("input[name='mnc']").val(`${data?.host?.phone?.mnc ?? 'Indispoível'}`);
                $("input[name='os_build_number']").val(`${data?.host?.phone?.os_build_number ?? 'Indispoível'}`);
                $("input[name='os_version']").val(`${data?.host?.phone?.os_version ?? 'Indispoível'}`);
                $("input[name='wa_version']").val(`${data?.host?.phone?.wa_version ?? 'Indispoível'}`);
                $("input[name='pushname']").val(`${data?.host?.pushname ?? 'Indispoível'}`);

                if(data?.host?.phone?.device_manufacturer){
                    $("#imgModelo").attr('src', `${ data?.host?.phone?.device_manufacturer == 'Apple' ? 'http://simpleicon.com/wp-content/uploads/apple.png' : `https://cdn-icons-png.flaticon.com/512/174/174836.png` }`);
                }
                $.LoadingOverlay('hide');

            },
            error: function(error) {

                $.LoadingOverlay('hide');
                Swal.fire({
                    class: 'error',
                    icon: 'error',
                    title: `Ops!`,
                    text: `${ error?.responseJSON?.message ?? 'Erro ao obter dados da sessão, verifique se a sua sessão está ativa.' }`,
                })

            },
        });

    }

    async function stopSession(id) {

        Swal.fire({
            title: 'Tem a certeza?',
            text: "A sessão será interrompida!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
        }).then(async (result) => {
            if (result.value) {

                await $.post({
                    url: `/painel/sessions/${id}/stop`,
                    method: `DELETE`,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": `${id}`,
                    },
                    beforeSend: function(data) {
                        $('.modal-body').LoadingOverlay('show');
                    },
                    success: function(data) {

                        Swal.fire({
                            class: 'success',
                            icon: 'success',
                            title: `${data?.success ? 'Finalizada' : 'Conectado'}`,
                            text: `${data?.message ? data?.message : 'Sessão conectada'}`,
                        }).then(() => {
                            window.location.reload();
                        })

                        $('.modal-body').LoadingOverlay('hide');
                    },
                    error: function(error) {

                        Swal.fire({
                            class: 'danger',
                            icon: 'danger',
                            title: `${data?.success ? 'Finalizada' : 'Indisponível'}`,
                            text: `${data?.message ? data?.message : 'Impossível conectar com o WhatsApp'}`,
                        }).then(() => {
                            window.location.reload();
                        })

                        $('.modal-body').LoadingOverlay('hide');
                    },
                });


            }
        });

    }
</script>
@endsection
