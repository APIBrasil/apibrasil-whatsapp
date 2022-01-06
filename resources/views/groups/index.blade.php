@extends('layouts.app-master')

@section('content')
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

    @include('layouts.partials.navbar')

    <div class="page-wrapper">

        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="page-title mb-0 p-0">Divulga grupos</h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/painel">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Meus grupos</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-md-6 col-4 align-self-right">

                    @if($sessions->count() > 0)
                        <a href="#" class="btn btn-success text-white btn-sm float-end" onclick="showModalSend()"> <i class="fas fa-paper-plane"></i> Envio em massa</a>
                    @endif

                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card row p-3">

                @if( !isset(\Auth::user()->server_whatsapp))
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-cloud"></i> Você precisa configurar um <strong> servidor de whatsapp </strong> para poder divulgar em grupos.
                </div>

                @endif

                <div class="mt-2">
                    @include('layouts.partials.messages')
                </div>

                @can('grupos-visualizar')
                <div class="table-responsive">

                    <table class="table table-striped" id="tableList" width="100%">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Tag</th>
                                <th>Msgs</th>
                                <th>Membros</th>
                                <th>Responsável</th>
                                <th>Criado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                @else
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-cloud"></i> Você não permissão para visualizar isso, solicite em contato@apigratis.com.br.
                </div>
                @endcan

            </div>

        </div>

        @include('layouts.partials.footer')

    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalBuscaGrupos" tabindex="-1" aria-labelledby="modalBuscaGruposLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalBuscaGruposLabel">Buscar grupos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <div class="row">

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="category_id">Buscar por categoria</label>
                        <select class="form-control" id="category_id" name="category_id">
                            <option value="">Selecione</option>
                        </select>
                    </div>
                </div>

            </div>

            <hr />

            <div class="row ">
                <table class="table table-striped" id="show-groups">
                    <thead>
                        <tr>
                            <th style="width:20px;">Avatar</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Link</th>
                            <th>Criado</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
           <!--  <button type="button" class="btn btn-primary" onclick="getCategorysGroups()">Buscar </button> -->
        </div>
        </div>
    </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalSend" tabindex="-1" aria-labelledby="modalSendLabel" aria-hidden="true" style="z-index:99999999999">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">

                    <div class="row">
                        <input type="hidden" class="form-control" id="group_id" name="group_id" />

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="message" class="col-form-label">Digite o texto</label>
                                <textarea class="form-control" id="message" name="message" rows="10" placeholder="Digite o texto a ser enviado."></textarea>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="file" class="form-label">Arquivo anexo</label>
                                <input type="file" class="form-control" id="file" name="file" />
                            </div>
                        </div>

                        <div class="col-sm-6" id="show_tags" disabled>
                            <div class="form-group">
                                <label for="tag_id" class="form-label">Enviar para tag</label>
                                <select class="form-control" name="tag_id" disabled>
                                    <option value="">Selecione</option>
                                    @forelse ($allTags as $item)
                                    <option value="{{$item->id}}"> {{ $item->name }} </option>
                                    @empty
                                    @endforelse
                                </select>

                                @if ($errors->has('name'))
                                <span class="text-danger text-left">{{ $errors->first('tag_id') }}</span>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="hide">
                        <hr/>
                        <table class="table table-striped" id="tableListSends" width="100%">
                            <thead>
                                <tr>
                                    <th>Telefone</th>
                                    <th>Mensagem</th>
                                    <th>Status</th>
                                    <th>Enviado</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="btnSend">Enviar mensagem</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalShowGroup" tabindex="-1" aria-labelledby="modalShowGroupLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">

                    <div class="row">

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="show_id" class="form-label">ID do Grupo</label>
                                <input class="form-control" name="show_id" disabled />
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="show_name" class="form-label">Nome</label>
                                <input class="form-control" name="show_name" disabled />
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-2">
                            <img src="" name="pic_show" id="pic_show"/>
                        </div>

                        <div class="col-sm-10">
                            <div class="form-group">
                                <textarea class="form-control" name="show_desc" disabled rows="7"></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="show_desc_id" class="form-label">ID descrição</label>
                                <input class="form-control" name="show_desc_id" disabled />
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="show_creation" class="form-label">Criado em</label>
                                <input class="form-control" name="show_creation" disabled />
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="show_status" class="form-label">Status atual</label>
                                <input class="form-control" name="show_status" disabled />
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="show_desc_owner" class="form-label">Quem alterou a descrição</label>
                                <input class="form-control" name="show_desc_owner" disabled />
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="descTime" class="form-label">Quando alterou a decrição</label>
                                <input class="form-control" name="descTime" disabled />
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="show_owner_user" class="form-label">Dono do grupo</label>
                                <input class="form-control" name="show_owner_user" disabled />
                            </div>
                        </div>
                    </div>

                    <hr />

                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="show_subject_owner" class="form-label">Quem alterou o nome</label>
                                <input class="form-control" name="show_subject_owner" disabled />
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="show_subject_time" class="form-label">Quando alterou o nome</label>
                                <input class="form-control" name="show_subject_time" disabled />
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="show_size" class="form-label">Quantidade participantes</label>
                                <input class="form-control" name="show_size" disabled />
                            </div>
                        </div>

                    </div>
                    <h4>Participantes</h4>
                    <div class="row list_participants">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form method="POST" action="{{ route('groups.store') }}" id="formSubmit">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCreateLabel">Criar grupo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-sm-6">
                                <label for="name" class="form-label">Nome do grupo</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="" required />
                            </div>

                            <div class="col-sm-6">
                                <label for="description" class="form-label">Descrição do grupo</label>
                                <input type="text" class="form-control" id="description" name="description" placeholder="" required />
                            </div>

                            <div class="col-sm-12">
                                <hr />
                            </div>

                            <div class="col-sm-12">
                                <label for="desc" class="form-label">Participantes</label>

                                <div class="row" style="padding-left:10px;max-height: 260px; overflow-y:scroll">
                                    <div class="form-check col-3">
                                        <input class="form-check-input" type="checkbox" name="all" id="all" onclick="$('input[type=checkbox]').attr('checked', true);"/>
                                        <label class="form-check-label" for="all">
                                            <strong> Marcar todos </strong>
                                        </label>
                                    </div>
                                    @forelse ($contacts->take(10) as $contact)
                                    <div class="form-check col-3">
                                        <input class="form-check-input" type="checkbox" name="contacts[]" id="contacts{{$contact->id}}" value="{{ $contact->phone }}" />
                                        <label class="form-check-label" for="contacts{{$contact->id}}">
                                            {{ $contact->name ? $contact->name : 'Sem nome' }} ({{ $contact->phone }})
                                        </label>
                                    </div>
                                    @empty
                                    <h4> Sem contatos </h4>
                                    @endforelse
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary" onclick="$(this).html('Criando...').attr('disabled', true); setTimeout( () => $(this).html('Cadastrar').attr('disabled', false), 5000);$('#formSubmit').submit()">Cadastar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endsection
    @section('scripts')

    @php $auth_id = \Auth::user()->id @endphp
    <script>
        $(() => {

            tableDefault = $('#tableList').DataTable({
                "lengthMenu": [[10, 25, 50, 100, 200, 300, -1], [10, 25, 50, 100, 200, 300, "Todos"]],
                "aaSorting": [[ 7, "DESC" ]],
                "dom": "'B'"+"<'row'<'col-sm-6'l><'col-sm-6'f>>\
                <'row'<'col-sm-12'<'table-responsive't>r>>\
                <'row'<'col-sm-5'i><'col-sm-12'p>>",
                buttons: [
                    {
                        text: '<i class="fas fa-sync"></i>',
                        action: function(e, dt, node, config) {
                            tableDefault.ajax.reload()
                        },
                        titleAttr: 'Atualizar tabela',
                    },
                   /*  {
                        extend:    'selectNone',
                        className: 'hide btn-selection-none',
                        text:      '<i class="fas fa-check"></i>',
                        titleAttr: 'Limpar seleção'
                    }, */
                    {
                        extend:    '',
                        className: 'bg-info',
                        text:      '<i class="fas fa-search"></i>',
                        titleAttr: 'Extrair grupos',
                        action: () => {
                            sincronizarGrupos('{{ Auth::id() }}')
                        },
                    },
                    {
                        extend:    '',
                        className: 'bg-primary',
                        text:      '<i class="fas fa-plus"></i>',
                        titleAttr: 'Criar grupo',
                        action: () => {
                            openModalCreate();
                        },
                    },
                    /* {
                        extend:    'selectAll',
                        className: 'btn-selection-all',
                        text:      '<i class="fas fa-check-double"></i>',
                        titleAttr: 'Selecionar todos'
                    }, */
                    {
                        text: '<i class="fas fa-trash"></i>',
                        className: 'bg-danger',
                        action: function(e, dt, node, config) {
                            deleteAllGroups()
                        },
                        titleAttr: 'Limar tudo'
                    },
                ],
                lengthChange: true,
                autoFill: true,
                select: { style: 'multi' },
                processing: false,

                deferRender: true,
                cache: true,
                destroy: true,
                serverSide: false,
                stateSave: true,
                searchDelay: 350,
                search: {
                    "smart": true,
                    "caseInsensitive": false
                },
                ajax: '/painel/groups/datatables',
                "columnDefs": [
                    {"targets": 0, "render": function (data, type, row) {
                        return `<a href="#" onclick="showInfo('${ row?.id}');" title="Ver informações em tempo real">${ row?.subject?.length > 3 ? row?.subject : 'Sem nome'} </a>`
                    }},
                    {"targets": 1, "render": function (data, type, row) {
                        return `<span title="${row?.desc || 'Sem descrição'}" > ${ row?.desc?.length > 3 ? row?.desc.substring(0, 20) : 'Indisponível'} </span>`
                    }},
                    {"targets": 2, "render": function (data, type, row) {
                        return `${ row?.tags?.name ? `<i class="fas fa-tags"></i> ${row?.tags?.name}` : ''}</a>`
                    }},
                    {"targets": 3, "render": function (data, type, row) {
                        return `${ row?.messages_groups?.length > 0 ? row?.messages_groups?.length : row?.messages_groups?.length }`
                    }},
                    {"targets": 4, "render": function (data, type, row) {
                        return `${row?.size ? row?.size : 0}`
                    }},
                    {"targets": 5, "render": function (data, type, row) {
                        return `${ row?.user?.username ?? ''}`
                    }},
                    {"targets": 6, "render": function (data, type, row) {
                        return `${ moment().diff(row?.update_at, 'days') < 1 ? `${ moment(row?.update_at).format('DD/MM/YYYY HH:mm') } <span class="badge bg-success" title="Importado recente">N</span>` : ''}`
                    }},
                    {"targets": 7, "render":  (data, type, row) => {

                        let modalSend = `<a class="btn btn-success btn-sm" href="#" onclick="openModalSend('${row?.group_id}', ${row?.user_id});$('.hide').css('display', 'block');"> <i class="far fa-paper-plane"></i></a>`
                        let btnEdit = `@can('grupos-editar')<a href="/painel/groups/${row?.id}/edit" class="btn btn-info btn-sm"> <i class="fas fa-edit"></i> </a>@endcan`
                        let btnDelete = `@can('grupos-deletar')<a class="btn btn-sm btn-danger" onclick="dropItem(${row?.id})" > <i class="fas fa-trash-alt"></i> </a>@endcan`
                        let btnExtra = `${row?.link ? `<a class="btn btn-sm btn-primary" href="${row?.link || '#'}" target="_blank"> <i class="fas fa-external-link-square-alt"></i> </a>` : ''}`

                        return `${modalSend} ${btnEdit} ${btnDelete} ${btnExtra}`;

                    }}
                ],
                'language': {
                    "lengthMenu": "_MENU_ p/ página",
                    "search": "Buscar: ",
                    "loadingRecords": "Aguarde, carregando...",
                    "processing": "Aguarde, carregando...",
                    "info":           "Mostrando _START_ a _END_ de _TOTAL_ dados",
                    "paginate": {
                            "first":      "Primeiro",
                            "last":       "Último",
                            "next":       "Próximo",
                            "previous":   "Anterior"
                    },
                    "zeroRecords":    "Opa, nenhuma informação foi localizado.",
                },
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
            });

            $(".table-responsive #tableList_wrapper .dt-buttons button").css('margin-right', '5px')

            tableListSends = $('#tableListSends').DataTable({
                'paging': false,
                'select': true,
                'responsive': true,
                "order": [
                    [3, "desc"]
                ],
            });

           /*  table = $('#tableList').DataTable({
                'paging': false,
                'select': true,
                'responsive': true,
                "order": [
                    [6, "desc"]
                ],
            }); */

            $('#tableList tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');

                var pos = tableDefault.row(this).index();
                var row = tableDefault.row(pos).data();

            });

            $('#modalSend').on('show.bs.modal', function(e) {

                $('#modalSend').modal({ backdrop: 'static' });

            }).on('hide.bs.modal', function(e) {

                $('.hide').css('display', 'block');

                $("select[name='tag_id']").val('');
                $("select[name='tag_id']").attr('disabled', true);

                tableDefault.ajax.reload()

            })

            file64 = ""
            $("#file").on('change', async function(e) {

                let reader = new FileReader();
                let file = await $("#file")[0].files[0]

                await reader.readAsDataURL(file);
                reader.onload = () => {
                    file64 = reader.result;
                };
            })

            $("#category_id").on('change', async function(e) {

                category_id = $("#category_id option:selected").val();

                await $.post({
                    url: `https://api.linkgrupos.app/partner/group/${category_id}/list`,
                    method: `GET`,
                    headers: {
                        'token' : ''
                    },
                    beforeSend: function(data) {
                        $.LoadingOverlay('show');
                    },
                    success: function(data) {

                        data?.groups?.forEach(group => {

                            tableGroups = $("#show-groups").DataTable().row.add([
                                group?.avatar ? `<img src="${group?.avatar}" style="width: 75%"/>` : '-',
                                group?.group ?? `${group?.name}`,
                                group?.description ? `${group?.description.substr(0, 10)}...` : '-',
                                group?.url ? `<a href="#" onclick="entrarGrupo('${group?.url}')"> Entrar no grupo </a>` : 'Sem link',
                                group?.created ? `${ moment(group?.created).format('DD/MM/Y HH:MM') }` : '-',
                            ]).draw(false);

                        })

                        $.LoadingOverlay('hide');

                    },
                    error: function(error) {

                        $.LoadingOverlay('hide');
                        Swal.fire({
                            class: 'error',
                            icon: 'error',
                            title: `Não importado`,
                            text: `Erro ao obter informações.`,
                        })

                    },
                });


            })

            $("#btnSend").on("click", () => {
                $("#modalSend").modal('hide');
            })

        })

        async function showInfo(id) {

            await $.post({
                url: `/painel/groups/online/${id}/show`,
                method: `POST`,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "group_id": `${id}`,
                },
                beforeSend: function(data) {
                    $.LoadingOverlay('show');
                },
                success: function(data) {

                    if( data?.result === 401){
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
                    $('#modalShowGroup').modal('show');

                    $("input[name='show_id']").val(`${data?.convite?.id?.user || 'Privado'}`);
                    $("input[name='show_name']").val(`${data?.convite?.subject || 'Privado'}`);
                    $("textarea[name='show_desc']").val(`${data?.convite?.desc || 'Privado'}`);
                    $("input[name='show_desc_id']").val(`${data?.convite?.descId || 'Privado'}`);

                    $("input[name='show_creation']").val(`${moment(data?.convite?.creation).format('DD/MM/Y HH:MM') || 'Privado'}`);
                    $("input[name='show_status']").val(`${ data?.convite?.status || 'Privado ou inválido' }`);


                    $("input[name='descTime']").val(`${moment(data?.convite?.descTime).format('DD/MM/Y HH:MM') || 'Privado'}`);
                    $("input[name='show_owner_user']").val(`${data?.convite?.owner?.user || 'Privado'}`);

                    $("input[name='show_subject_owner']").val(`${data?.convite?.subjectOwner?.user || 'Privado'}`);
                    $("input[name='show_desc_owner']").val(`${data?.convite?.descOwner?.user || 'Privado'}`);

                    $("input[name='show_subject_time']").val(`${ moment(data?.convite?.subjectTime).format('DD/MM/Y HH:MM') || 'Privado'}`);
                    $("input[name='show_size']").val(`${ data?.convite?.size || 'Privado'}`);

                    $("img[name='pic_show']").attr('src', `${ data?.outros?.pic_profile?.eurl ? data?.outros?.pic_profile?.eurl : 'https://st3.depositphotos.com/23594922/31822/v/600/depositphotos_318221368-stock-illustration-missing-picture-page-for-website.jpg'}`);
                    $("img[name='pic_show']").css('width', '100%');

                    if(typeof data?.convite?.participants === 'undefined') {
                        $(".list_participants").html(`<div class="col-sm-3">Informação privada.</div>`);
                    }else{
                        $(".list_participants").html(``);
                    }

                    qt = 0;
                    data?.convite?.participants?.forEach(participant => {

                        qt++
                        $(".list_participants").append(`
                            <div class="col-sm-2 mt-3">
                                <a href="#" onclick="openModalSend('${participant?.id?.user}', '{{ auth()->user()->id }}', false);$('.hide').css('display', 'none');" > ${participant?.id?.user || ''} </a> <br />
                                ${participant?.isAdmin ? '<strong> <i class="fas fa-star text-warning"></i> Administrador</strong>' : 'Não administrador'}<br />
                                ${participant?.isSuperAdmin ? '<strong><i class="fas fa-star text-danger"></i> Super Admin</strong>' : 'Não é super admin'}<br />
                            </div>
                        `);

                    })

                    $("input[name='show_size']").val(`${ data?.convite?.size || qt }`);
                    $.LoadingOverlay('hide');

                },
                error: function(error) {

                    $.LoadingOverlay('hide');
                    Swal.fire({
                        class: 'error',
                        icon: 'error',
                        title: `Ops!`,
                        text: `${ error?.responseJSON?.message || 'Erro ao obter categorias, verifique se a sua sessão está conectada.' }`,
                    })

                },
            });

        }

        async function entrarGrupo(url) {

            Swal.fire('Em breve', 'Aguarde alguns dias para esta funcionalidade.', 'info')

        }

        async function showModalSend() {

            await $('.hide').css('display', 'none');
            await $("select[name='tag_id']").attr('disabled', false);
            await $("#btnSend").attr('onclick', `sendText('{{$auth_id}}', false)`);

            await $('#modalSend').modal('show');

        }

        async function openModalCreate() {
            $('#modalCreate').modal('show');
        }

        async function openModalSend(group_id, user_id) {

            $("#group_id").val(group_id);
            $("#btnSend").attr('onclick', `sendText('${user_id}', false)`);

            await $.post({
                url: `/painel/groups/${group_id}/show`,
                method: `POST`,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "group_id": `${group_id}`,
                },
                beforeSend: function(data) {
                    $.LoadingOverlay('show');
                },
                success: function(data) {

                    $("#tableListSends").DataTable().clear();

                    data?.group?.messages_light?.forEach((data) => {

                        tableListSends = $("#tableListSends").DataTable().row.add([
                            data?.number ? `Grupo` : `${data?.number}`,
                            data?.message ? `${data?.message.substr(0, 10)}...` : '-',
                            data?.status ? `${data?.status}` : '-',
                            data?.created_at ? `${ moment(data?.created_at).format('DD/MM/Y HH:MM') }` : '-',
                        ]).draw(false);
                    })

                    $(".modal-title").html(`Enviar mensagem`);
                    $('#modalSend').modal('show');

                    $.LoadingOverlay('hide');

                },
                error: function(error) {

                    $.LoadingOverlay('hide');
                    Swal.fire({
                        class: 'error',
                        icon: 'error',
                        title: `Não importado`,
                        text: `Erro ao obter informações.`,
                    })

                },
            });
        }

        async function openModalGetGroups () {

            await $.post({
                url: `https://api.linkgrupos.app/partner/category/list`,
                method: `GET`,
                headers: {
                    'token' : ''
                },
                beforeSend: function(data) {
                    $.LoadingOverlay('show');
                },
                success: function(data) {

                    if(data?.length > 0) {

                        data.forEach((data) => {

                            $("#category_id").append(`<option value="${data.categoryId}">${data.categoryName}</option>`);

                        })

                    }

                    $.LoadingOverlay('hide');
                    $("#modalBuscaGrupos").modal('show');

                },
                error: function(error) {

                    $.LoadingOverlay('hide');
                    Swal.fire({
                        class: 'error',
                        icon: 'error',
                        title: `Não importado`,
                        text: `${ error?.responseJSON?.message || 'Erro ao obter categorias, verifique se a sua sessão está conectada.' }`,
                    })

                },
            });

        }

        async function sincronizarGrupos(user_id) {

            await $.post({
                url: `/painel/groups/get`,
                method: `POST`,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "user_id": `${user_id}`,
                },
                beforeSend: function(data) {
                    $.LoadingOverlay('show');
                },
                success: function(data) {

                    if(data?.groups?.length > 0) {
                        window.location.reload()
                    }

                    $.LoadingOverlay('hide');
                },
                error: function(error) {

                    $.LoadingOverlay('hide');
                    Swal.fire({
                        class: 'error',
                        icon: 'error',
                        title: `Não importado`,
                        text: `${ error?.responseJSON?.message || 'Erro ao obter grupos, verifique se a sua sessão está conectada.' }`,
                    })

                },
            });

        }

        async function dropItem(id){

            $.confirm({
                title: 'Confirmação',
                content: 'Deseja mesmo deletar esse grupo?',
                dragWindowBorder: true,
                animationBounce: 1.5,
                theme: 'modern',
                buttons: {
                    Cancelar: {
                    btnClass: 'btn-green',
                    keys: ['enter', 'space'],
                    action: function(){
                    }
                },
                Deletar: {
                    btnClass: 'btn-red',
                    keys: ['enter', 'space'],
                    action: function(){

                        $.LoadingOverlay('show');

                        $.post({
                            url: `/painel/groups/${id}/delete`,
                            method: "delete",
                            data: {
                                '_token': $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function(retorno){
                                if(retorno != 'undefined'){
                                    tableDefault.ajax.reload();

                                };

                                $.LoadingOverlay('hide');

                            },
                            error: function(error) {
                                $.LoadingOverlay("hide");

                            },
                        });
                    }
                }
                }
            });

        }

        async function deleteAllGroups(user_id) {

            Swal.fire({
                title: 'Deseja mesmo deletar todos os grupos?',
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'Sim',
            }).then((result) => {

                if (result.isConfirmed) {

                    $.post({
                        url: `/painel/groups/deleteAll`,
                        method: `DELETE`,
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        beforeSend: function(data) {
                            $.LoadingOverlay('show');
                        },
                        success: function(data) {

                            $.LoadingOverlay('hide');
                            Swal.fire('Limpo!', '', 'success')

                            tableDefault.ajax.reload();

                        },
                        error: function(error) {

                            $.LoadingOverlay('hide');
                            Swal.fire({
                                class: 'error',
                                icon: 'error',
                                title: `Ops!`,
                                text: `Erro ao deletar grupos.`,
                            })

                        },
                    });

                } else if (result.isDenied) {

                    Swal.fire('Sem alterações', '', 'info')
                }

            })

        }
    </script>
    @endsection
