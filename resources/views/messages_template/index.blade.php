@extends('layouts.app-master')

@section('content')
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

@include('layouts.partials.navbar')

<div class="page-wrapper">

    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="page-title mb-0 p-0">Layouts de mensagens</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('templates.index') }}">Layouts</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Mensagens</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-md-6 col-4 align-self-right">
                <a href="#" class="btn btn-info text-white btn-sm float-end" style="margin-right:5px;"> <i class="fas fa-plus"></i> </a>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-0">
        <div class="card">
            <div class="card-body">

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

            </div>
        </div>


    </div>

    @include('layouts.partials.footer')

</div>

<!-- Modal -->
<div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('contacts.store') }}" method="post">
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

@endsection

@php $auth_id = \Auth::user()->id @endphp
@section('scripts')
<script>
    $(async function(document) {

        await getTableDefault();

        $('#modalSend').on('show.bs.modal', function(e) {

            $('#modalSend').modal({
                backdrop: 'static'
            })

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

        $('#modalImport').modal({
            backdrop: 'static'
        })
        $('#modalImport').modal('show');

    }

    async function getTableDefault() {

        tableDefault = $('#tableDefault').DataTable({
            "lengthMenu": [
                [10, 25, 50, 100, 200, 300, -1],
                [10, 25, 50, 100, 200, 300, "Todos"]
            ],
            "aaSorting": [
                [7, "DESC"]
            ],
            "dom": "'B'" + "<'row'<'col-sm-6'l><'col-sm-6'f>>\
            <'row'<'col-sm-12'<'table-responsive't>r>>\
            <'row'<'col-sm-5'i><'col-sm-12'p>>",
            buttons: [{
                    text: '<i class="fas fa-sync"></i>',
                    action: function(e, dt, node, config) {
                        tableDefault.ajax.reload()
                    },
                    titleAttr: 'Atualizar tabela',
                },
                {
                    extend: '',
                    className: 'bg-primary',
                    text: '<i class="fas fa-user-plus"></i>',
                    titleAttr: 'Adicionar contato',
                    action: () => {
                        openModalAdd();
                    },
                },
            ],
            lengthChange: true,
            autoFill: true,
            select: {
                style: 'multi'
            },
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
            "columnDefs": [{
                    "targets": 0,
                    "render": function(data, type, row) {
                        return `<img src="/user.png" alt="${row?.name ?? ''}" style='width:32px' />`
                    }
                },
                {
                    "targets": 1,
                    "render": function(data, type, row) {
                        return `${ row?.name?.length > 3 ? row?.name : 'Sem nome'}`
                    }
                },
                {
                    "targets": 2,
                    "render": function(data, type, row) {
                        return `${ row?.phone } ${ moment().diff(row?.created_at, 'days') < 1 ? '<span class="badge bg-success" title="Importado recente">N</span>' : ''}`
                    }
                },
                {
                    "targets": 3,
                    "render": function(data, type, row) {
                        return `${ row?.messages_contact?.length > 5 ? `${row?.messages_contact?.length} <i class="fas fa-exclamation text-danger" title="Envios frequentes"></i>` : row?.messages_contact?.length}`
                    }
                },
                {
                    "targets": 4,
                    "render": function(data, type, row) {
                        return `${ row?.tag?.name ? `<i class="fas fa-tags"></i> ${row?.tag?.name}` : ''}`
                    }
                },
                {
                    "targets": 5,
                    "render": function(data, type, row) {
                        return `${row?.group?.name ?? ''}`
                    }
                },
                {
                    "targets": 6,
                    "render": function(data, type, row) {
                        return `${ row?.user?.username ?? ''}`
                    }
                },
                {
                    "targets": 7,
                    "render": function(data, type, row) {
                        return `${ moment(row?.created_at).format('DD/MM/YY HH:mm') ?? ''}`
                    }
                },
                {
                    "targets": 8,
                    "render": (data, type, row) => {

                        let btnEdit = `@can('contatos-editar')<a href="/painel/contacts/${row?.id}/edit" class="btn btn-info btn-sm"> <i class="fas fa-edit"></i> </a>@endcan`
                        let btnDelete = `@can('contatos-deletar')<a class="btn btn-sm btn-danger" onclick="dropItem(${row?.id})" > <i class="fas fa-trash-alt"></i> </a>@endcan`

                        return `${btnEdit} ${btnDelete}`;

                    }
                }
            ],
            'language': {
                "lengthMenu": "_MENU_ p/ página",
                "search": "Buscar: ",
                "loadingRecords": "Aguarde, carregando...",
                "processing": "Aguarde, carregando...",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ dados",
                "paginate": {
                    "first": "Primeiro",
                    "last": "Último",
                    "next": "Próximo",
                    "previous": "Anterior"
                },
                "zeroRecords": "Opa, nenhuma informação foi localizado.",
            },
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
        });

        $(".table-responsive #tableDefault_wrapper .dt-buttons button").css('margin-right', '5px')

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

                if (data?.contacts?.length > 0) {

                    Swal.fire({
                        class: 'success',
                        icon: 'success',
                        title: `Importado com sucesso!`,
                        text: `Contatos importados com sucesso.`,
                    })

                } else {
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

    async function showModalSend() {

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

    async function dropItem(id) {

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
                    action: function() {}
                },
                Deletar: {
                    btnClass: 'btn-red',
                    keys: ['enter', 'space'],
                    action: function() {

                        $.LoadingOverlay('show');

                        $.post({
                            url: `/painel/contacts/${id}/delete`,
                            method: "delete",
                            data: {
                                '_token': $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function(retorno) {
                                if (retorno != 'undefined') {
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
