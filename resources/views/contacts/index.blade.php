@extends('layouts.app-master')

@section('content')
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

@include('layouts.partials.navbar')

<div class="page-wrapper">

    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="page-title mb-0 p-0">Divulgação direta</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Divulgação</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Contatos</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-md-6 col-4 align-self-right">

                @if($sessions->count() > 0)
                    <a href="{{ route('contacts.export') }}" class="btn btn-info text-white btn-sm float-end" style="margin-right:5px;"> <i class="fas fa-download"></i> </a>
                    <a href="#" class="btn btn-success text-white btn-sm float-end" style="margin-right:5px;" onclick="showModalSend()"> <i class="fas fa-paper-plane"></i> Envio em massa</a>
                @endif

            </div>
        </div>
    </div>

    <div class="container-fluid mt-0">
        <div id="tabs" style="border:0px;">
            <ul style="padding-left:1.2em;border:0px;background:white;padding-top:5px;">
                <li><a href="#tabs-1"> <i class="fas fa-users"></i> {{ $count_importeds['normal'] }} Contatos</a></li>
                <li><a href="#tabs-2"> <i class="fas fa-file-excel"></i> {{ $count_importeds['importados'] }} Importados </a></li>
            </ul>
            <div id="tabs-1">

                <div class="card">

                    @if( !isset(\Auth::user()->server_whatsapp))
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-cloud"></i> Você precisa configurar um <strong> servidor de whatsapp </strong>  para poder enviar mensagens.
                    </div>
                    @endif

                    <div class="mt-2">
                        @include('layouts.partials.messages')
                    </div>

                    @can('contatos-visualizar')
                    <div class="table-responsive">

                        <table class="table table-striped" id="tableDefault" width="100%">
                            <thead>
                                <tr>
                                    <th width="32">Avatar</th>
                                    <th>Nome</th>
                                    <th>Telefone</th>
                                    <th>Msg</th>
                                    <th>Tag</th>
                                    <th>Importado de</th>
                                    <th>Responsável</th>
                                    <th>Criado</th>
                                    <th style="width:110px">Ações</th>
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

            <div id="tabs-2">
                <div class="card">

                    @if( !isset(\Auth::user()->server_whatsapp))
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-cloud"></i> Você precisa configurar um <strong> servidor de whatsapp </strong>  para poder enviar mensagens.
                    </div>
                    @endif

                    <div class="mt-2">
                        @include('layouts.partials.messages')
                    </div>

                    @can('contatos-visualizar')
                    <div class="table-responsive">
                        <table class="table table-striped" id="tableImported" width="100%">
                            <thead>
                                <tr>
                                    <th width="32">Avatar</th>
                                    <th>Nome</th>
                                    <th>Telefone</th>
                                    <th>Msg</th>
                                    <th>Tag</th>
                                    <th>Grupo</th>
                                    <th>Responsável</th>
                                    <th>Importado</th>
                                    <th style="width:110px">Ações</th>
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

        </div>


    </div>

    @include('layouts.partials.footer')

</div>

<!-- Modal -->
<div class="modal fade" id="modalSend" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <label for="file" class="col-form-label">Arquivo anexo</label>
                            <input type="file" class="form-control" id="file" name="file" />
                        </div>
                    </div>

                    <div class="col-sm-6" id="show_tags" disabled>
                        <div class="form-group">
                            <label for="tag_id" class="col-form-label">Enviar para tag</label>
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

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="schedule" class="col-form-label">Agendar envio</label>
                            <select class="form-control" name="schedulle" id="schedule">
                                <option value="nao" selected> Não </option>
                                <option value="sim"> Sim </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="date_schedule_send" class="col-form-label">Data e hora</label>
                            <input type="datetime-local" class="form-control" id="date_schedule_send" placeholder="00/00/0000 00:00:00" name="date_schedule_send" />
                        </div>
                    </div>
                </div>
                <div class="hide">

                    <hr />
                    <table class="table table-striped" id="tableList" width="100%">
                        <thead>
                            <tr>
                                <th style="width:20% !important;">Telefone</th>
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
<div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('contacts.store') }}" method="post" >
                {{ csrf_field() }}
                <div class="modal-body">

                    <div class="row">
                        <input type="hidden" class="form-control" id="number" name="number" />

                        <div class="mb-3 col-6">
                            <label for="name" class="form-label">Name</label>
                            <input value="{{ old('name') }}" type="text" class="form-control" id="name" name="name" placeholder="" required>

                            @if ($errors->has('name'))
                            <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="mb-3 col-6">
                            <label for="phone" class="form-label">WhatsApp</label>
                            <input value="{{ old('phone') }}" type="text" class="form-control" id="phone" name="phone" placeholder="+5500000000000" required>

                            @if ($errors->has('phone'))
                            <span class="text-danger text-left">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>

                        <div class="mb-3 col-6">
                            <label for="tag" class="form-label">Tag</label>
                            <select class="form-control tag_id" name="tag_id" id="tag_id">
                                <option value="">Selecione</option>
                                @forelse ($tags as $item)
                                <option value="{{ $item->id }}"> {{ $item->name }}</option>
                                @empty
                                @endforelse
                            </select>

                            @if ($errors->has('tag'))
                            <span class="text-danger text-left">{{ $errors->first('tag') }}</span>
                            @endif
                        </div>

                        <div class="mb-3 col-6">
                            <label for="isBusiness" class="form-label">Tipo WhatsApp</label>
                            <select class="form-control" name="isBusiness" id="isBusiness" required>
                                <option value="">Selecione</option>
                                <option value="1">WhatsApp Business</option>
                                <option value="0">WhatsApp Comum</option>
                            </select>

                            @if ($errors->has('name'))
                            <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="btnSend">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImportLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('contacts.import') }}" method="post" enctype="multipart/form-data" >
                {{ csrf_field() }}
                <div class="modal-body">

                    <div class="row">

                        <div class="mb-3 col-12">
                            <label for="file_import" class="form-label">Arquivo excel (xls, xlsx)</label>
                            <input value="{{ old('file_import') }}" type="file" class="form-control" id="file_import" accept=".xls,.xlsx" name="file_import" required>

                            @if ($errors->has('file_import'))
                            <span class="text-danger text-left">{{ $errors->first('file_import') }}</span>
                            @endif
                        </div>

                        <div class="mb-3 col-12">
                            <div class="alert alert-danger" role="alert"> Arquivo de importação de exemplo: <a href="/importacao_exemplo.xlsx" target="_blank">Fazer download</a> </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="btnSend">Importar</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@php $auth_id = \Auth::user()->id @endphp
@section('scripts')
<script>
    $(async function (document) {

        await getTableDefault();
        await getContactTableImported();

        $("#number").mask('000000000000000');
        $("#phone").mask('000000000000000');
        $("#date_schedulle_send").mask('00/00/0000 00:00:00');

        $( "#tabs" ).tabs();

        $('#modalSend').on('show.bs.modal', function(e) {

            $('#modalSend').modal({backdrop: 'static'})

            }).on('hide.bs.modal', async function(e) {

                $(".hide").css('display', 'block');

                //tableList.destroy();
                $("select[id='tag_id']").attr('disabled', false);

            $("select[name='tag_id']").val('');
            $("select[name='tag_id']").attr('disabled', true);

            await getTableDefault();
            await getContactTableImported()

        })


    })

    async function showModalImport() {

        $('#modalImport').modal({backdrop: 'static'})
        $('#modalImport').modal('show');

    }

    async function getTableDefault(){

        tableDefault = $('#tableDefault').DataTable({
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
                /* {
                    extend:    'selectNone',
                    className: 'hide btn-selection-none',
                    text:      '<i class="fas fa-check"></i>',
                    titleAttr: 'Limpar seleção'
                }, */
                {
                    extend:    '',
                    className: 'bg-info',
                    text:      '<i class="fas fa-search"></i>',
                    titleAttr: 'Extrair dos grupos',
                    action: () => {
                        extrairContatos('{{ Auth::id() }}')
                    },
                },
                {
                    extend:    '',
                    className: 'bg-primary',
                    text:      '<i class="fas fa-user-plus"></i>',
                    titleAttr: 'Adicionar contato',
                    action: () => {
                        openModalAdd();
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
                        deleteAll('extract')
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
            serverSide: true,
            stateSave: true,
            searchDelay: 350,
            search: {
                "smart": true,
                "caseInsensitive": false
            },
            ajax: '/painel/contacts/datatables',
            "columnDefs": [
                {"targets": 0, "render": function (data, type, row) {
                    return `<img src="/user.png" alt="${row?.name ?? ''}" style='width:32px' />`
                }},
                {"targets": 1, "render": function (data, type, row) {
                    return `${ row?.name?.length > 3 ? row?.name : 'Sem nome'}`
                }},
                {"targets": 2, "render": function (data, type, row) {
                    return `${ row?.phone } ${ moment().diff(row?.created_at, 'days') < 1 ? '<span class="badge bg-success" title="Importado recente">N</span>' : ''}`
                }},
                {"targets": 3, "render": function (data, type, row) {
                    return `${ row?.messages_contact?.length > 5 ? `${row?.messages_contact?.length} <i class="fas fa-exclamation text-danger" title="Envios frequentes"></i>` : row?.messages_contact?.length}`
                }},
                {"targets": 4, "render": function (data, type, row) {
                    return `${ row?.tag?.name ? `<i class="fas fa-tags"></i> ${row?.tag?.name}` : ''}`
                }},
                {"targets": 5, "render": function (data, type, row) {
                    return `${row?.group?.name ?? ''}`
                }},
                {"targets": 6, "render": function (data, type, row) {
                    return `${ row?.user?.username ?? ''}`
                }},
                {"targets": 7, "render": function (data, type, row) {
                    return `${ moment(row?.created_at).format('DD/MM/YY HH:mm') ?? ''}`
                }},
                {"targets": 8, "render":  (data, type, row) => {

                    let modalSend = `@can('contatos-enviar-mensages')<a class="btn btn-success btn-sm" href="#" onclick="openModalSend('${row?.phone}', ${row?.user_id});$('.hide').css('display', 'block');"> <i class="far fa-paper-plane"></i></a>@endcan`
                    let btnEdit = `@can('contatos-editar')<a href="/painel/contacts/${row?.id}/edit" class="btn btn-info btn-sm"> <i class="fas fa-edit"></i> </a>@endcan`
                    let btnDelete = `@can('contatos-deletar')<a class="btn btn-sm btn-danger" onclick="dropItem(${row?.id})" > <i class="fas fa-trash-alt"></i> </a>@endcan`

                    return `${modalSend} ${btnEdit} ${btnDelete}`;

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

        $(".table-responsive #tableDefault_wrapper .dt-buttons button").css('margin-right', '5px')

        $(".btn-selection-all").on('click', () => {
            $(".btn-selection-none").removeClass("hide");
        })

        $(".btn-selection-none").on('click', () => {
            $(".btn-selection-none").addClass("hide");
        })
    }

    async function getContactTableImported(){

        tableImported = await $('#tableImported').DataTable({
            "lengthMenu": [[10, 25, 50, 100, 200, 300, -1], [10, 25, 50, 100, 200, 300, "Todos"]],
            "aaSorting": [[ 7, "DESC" ]],
            "dom": "'B'"+"<'row'<'col-sm-6'l><'col-sm-6'f>>\
            <'row'<'col-sm-12'<'table-responsive't>r>>\
            <'row'<'col-sm-5'i><'col-sm-12'p>>",
            buttons: [
                {
                    text: '<i class="fas fa-sync"></i>',
                    action: function(e, dt, node, config) {
                        tableImported.ajax.reload()
                    },
                    titleAttr: 'Atualizar tela'
                },
                {
                    extend:    'selectNone',
                    className: 'hide btn-selection-none',
                    text:      '<i class="fas fa-check"></i>',
                    titleAttr: 'Limpar seleção'
                },
                {
                    extend:    '',
                    className: 'bg-info',
                    text:      '<i class="fas fa-upload"></i>',
                    titleAttr: 'Importar via excel',
                    action: () => {
                        showModalImport()
                    },
                },
                {
                    text: '<i class="fas fa-trash"></i>',
                    className: 'bg-danger',
                    action: function(e, dt, node, config) {
                        deleteAll('imported')
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
            serverSide: true,
            stateSave: true,
            searchDelay: 350,
            search: {
                "smart": true,
                "caseInsensitive": false
            },
            ajax: '/painel/contacts/datatables/imported',
            "columnDefs": [
                {"targets": 0, "render": (data, type, row) => {
                    //let image = getImage(row?.phone);
                    image  = null;
                    return `<img src="/user.png" alt="${ image ? image : '/user.png'}" style='width:32px' />`
                }},
                {"targets": 1, "render": function (data, type, row) {
                    return `${ row?.name?.length > 3 ? row?.name : 'Sem nome'}`
                }},
                {"targets": 2, "render": function (data, type, row) {
                    return `${ row?.phone } ${ moment().diff(row?.created_at, 'days') < 1 ? '<span class="badge bg-success" title="Importado recente">N</span>' : ''}`
                }},
                {"targets": 3, "render": function (data, type, row) {
                    return `${ row?.messages_contact?.length > 5 ? `${row?.messages_contact?.length} <i class="fas fa-exclamation text-danger" title="Envios frequentes"></i>` : '0'}`
                }},
                {"targets": 4, "render": function (data, type, row) {
                    return `${ row?.tag?.name ? `<i class="fas fa-tags"></i> ${row?.tag?.name}` : ''}`
                }},
                {"targets": 5, "render": function (data, type, row) {
                    return `${row?.group?.name ?? ''}`
                }},
                {"targets": 6, "render": function (data, type, row) {
                    return `${ row?.user?.username ?? ''}`
                }},
                {"targets": 7, "render": function (data, type, row) {
                    return `${ moment(row?.created_at).format('DD/MM/YY HH:mm') ?? ''}`
                }},
                {"targets": 8, "render":  (data, type, row) => {

                    let modalSend = `@can('contatos-enviar-mensages')<a class="btn btn-success btn-sm" href="#" onclick="openModalSend('${row?.phone}', ${row?.user_id})"> <i class="far fa-paper-plane"></i></a>@endcan`
                    let btnEdit = `@can('contatos-editar')<a href="/painel/contacts/${row?.id}/edit" class="btn btn-info btn-sm"> <i class="fas fa-edit"></i> </a>@endcan`
                    let btnDelete = `@can('contatos-deletar')<a class="btn btn-sm btn-danger" onclick="dropItem(${row?.id})" > <i class="fas fa-trash-alt"></i> </a>@endcan`

                    return `${modalSend} ${btnEdit} ${btnDelete}`;

                }}
            ],
            "aoColumns": [
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                { "width": "120px"}
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

        $(".table-responsive #tableImported_wrapper .dt-buttons button").css('margin-right', '5px')

        $(".btn-selection-all").on('click', () => {
            $(".btn-selection-none").removeClass("hide");
        })

        $(".btn-selection-none").on('click', () => {
            $(".btn-selection-none").addClass("hide");
        })
    }

    async function extrairContatos(user_id) {

        await $.post({
            url: `/painel/contacts/extracts`,
            method: `POST`,
            cache: false,
            data: {
                "_token": "{{ csrf_token() }}",
                "user_id": `${user_id}`,
            },
            beforeSend: function(data) {
                $.LoadingOverlay('show');
            },
            success: function(data) {

                if(data?.contacts?.length > 0){

                    Swal.fire({
                        class: 'success',
                        icon: 'success',
                        title: `Importado com sucesso!`,
                        text: `Contatos importados com sucesso.`,
                    })

                }else{
                    Swal.fire({
                        class: 'warning',
                        icon: 'warning',
                        title: `Ops!`,
                        text: `Contatos não importados.`,
                    })

                }

                tableDefault.ajax.reload();
                $.LoadingOverlay('hide');

            },
            error: function(error) {

                $.LoadingOverlay('hide');
                Swal.fire({
                    class: 'error',
                    icon: 'error',
                    title: `Não importado`,
                    text: `Erro ao obter contatos, verifique se a sua sessão está conectada.`,
                })

            },
        });

    }

    async function showModalSend(){

        $('#modalSend').modal('show');
        $("select[name='tag_id']").attr('disabled', false);
        $('.hide').css('display', 'none');
        $("#btnSend").attr('onclick', `sendText('{{$auth_id}}', false)`);

        /* if(!tag_id) {
            Swal.fire({
                class: 'error',
                icon: 'error',
                title: `Uai...`,
                text: `Precisamos de uma tag.`,
            })
            return false;
        } */

    }

    async function openModalAdd(group_id, user_id) {
        $("#group_id").val(group_id);
        $("#btnSend").attr('onclick', `sendText('${user_id}', false)`);

        $(".modal-title").html(`Cadastrar contato`);
        $('#modalAdd').modal('show');
    }

    async function openModalSend(phone, user_id) {

        $("#tag_id").attr('required', true);

        await $.post({
            url: `/painel/contacts/${phone}/show`,
            method: `POST`,
            data: {
                "_token": "{{ csrf_token() }}",
            },
            beforeSend: (data) => {
                $.LoadingOverlay('show');
            },
            success: (data) => {

                $("#number").val(phone);
                $("#number").change();

                $("#btnSend").attr('onclick', `sendText('${user_id}')`);

                tableList = $("#tableList").DataTable().clear();

                data?.messages_contact?.forEach((data) => {

                    tableList = $("#tableList").DataTable().row.add([
                        data?.number?.length > 14 ? `Grupo` : `${data?.number}`,
                        data?.message?.length > 0 ? `${data?.message.substr(0, 10)}...` : '-',
                        data?.status?.length > 0 ? `${data?.status}` : '-',
                        data?.send_at?.length > 0 ? `${ moment(data?.send_at).format('DD/MM/Y HH:mm:ss') }` : '00/00/0000 00:00:00',
                    ]).draw(false);
                })

                $(".modal-title").html(`Enviar mensagem`);
                $('#modalSend').modal('show');
                tableDefault.ajax.reload();

                $.LoadingOverlay('hide');

            },
            error: (error) => {

                $.LoadingOverlay('hide');
            },
        });

    }

    async function dropItem(id){

        $.confirm({
            title: 'Confirmação',
            content: 'Deseja mesmo deletar esse contato?',
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
                        url: `/painel/contacts/${id}/delete`,
                        method: "delete",
                        data: {
                            '_token': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(retorno){
                            if(retorno != 'undefined'){
                                tableDefault.ajax.reload();
                                tableImported.ajax.reload();

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

    async function getImage(id) {
            $.post({
                url: `/painel/groups/images/${id}/get`,
                method: "GET",
                success: function(retorno){
                    //console.log(retorno.image?.pic_profile?.eurl);
                    retorno.image?.pic_profile?.eurl;

                },
            });
    }

    async function deleteAll(situacao){

        $.confirm({
            title: 'Confirmação de exclusão',
            content: 'Ao confirmar, você irá limpar todos os contatos da sua lista atual.',
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
                        url: `/painel/contacts/destroy/${situacao}`,
                        method: "delete",
                        data: {
                            '_token': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(retorno){
                            if(retorno != 'undefined'){
                                tableDefault.ajax.reload();
                                tableImported.ajax.reload();

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

</script>
@endsection
