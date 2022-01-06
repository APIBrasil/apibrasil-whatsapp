@extends('layouts.app-master')

@section('content')
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

    @include('layouts.partials.navbar')

    <div class="page-wrapper">

        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="page-title mb-0 p-0">Mensagens enviadas</h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/painel">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Mensagens</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card row pb-3">

                <div class="mt-2">
                    @include('layouts.partials.messages')

                    @if($mensagensNaoEnviadas > 0)
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-life-ring"></i> Existem {{$mensagensNaoEnviadas}} mensagens não enviadas, verifique o status da sua sessão. <a href="/painel/sessions"> Gerenciar sessões </a>
                    </div>
                    @else
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check"></i> Você já enviou {{$mensagensEnviadas}} mensagens
                    </div>
                    @endif

                </div>

                <table class="table table-striped" id="table_id" width="100%">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Nome</th>
                            <th>Mensagem</th>
                            <th>Responsável</th>
                            <th>Situação</th>
                            <th>Criada</th>
                            <th>Enviada</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>

    </div>

    @include('layouts.partials.footer')

</div>

<!-- Modal -->
<div class="modal fade" id="modalShow" tabindex="-1" aria-labelledby="modalShowLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalShowLabel">Visualizar mensagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <textarea id="message" rows="18" class="form-control" placeholder=""></textarea>

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
    $(async (document) => {


        await getTable();

        /*
                table = $('#table_id').DataTable({
                    'paging': false,
                    "order": [[ 6, "desc" ]],
                });

                $('#table_id tbody').on('click', 'tr', function() {
                    $(this).toggleClass('selected');
                    var pos = table.row(this).index();
                    var row = table.row(pos).data();
                }); */

    })

    async function getTable() {

        tableImported = await $('#table_id').DataTable({
            "lengthMenu": [
                [10, 25, 50, 100, 200, 300, -1],
                [10, 25, 50, 100, 200, 300, "Todos"]
            ],
            "aaSorting": [
                [5, "DESC"]
            ],
            "dom": "'B'" + "<'row'<'col-sm-6'l><'col-sm-6'f>>\
    <'row'<'col-sm-12'<'table-responsive't>r>>\
    <'row'<'col-sm-5'i><'col-sm-12'p>>",
            buttons: [{
                    text: '<i class="fas fa-sync"></i>',
                    action: function(e, dt, node, config) {
                        tableImported.ajax.reload()
                    },
                    titleAttr: 'Atualizar tela'
                },
                {
                    extend: 'selectNone',
                    className: 'hide btn-selection-none',
                    text: '<i class="fas fa-check"></i>',
                    titleAttr: 'Limpar seleção'
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
            serverSide: false,
            stateSave: true,
            searchDelay: 350,
            search: {
                "smart": true,
                "caseInsensitive": false
            },
            ajax: '/painel/message/datatables',
            "columnDefs": [{
                    "targets": 0,
                    "render": (data, type, row) => {
                        return `${ row?.type ? row?.type : 'Sem type'}`
                    }
                },
                {
                    "targets": 1,
                    "render": function(data, type, row) {
                        return `${ row?.name?.length > 3 ? row?.name : 'Sem nome'}<br/> <i class="fas fa-mobile-alt"></i> ${ row?.number > 3 ? row?.number : 'Sem numero'}`
                    }
                },
                {
                    "targets": 2,
                    "render": function(data, type, row) {

                        let icon = row?.schedule == 'sim' && row?.date_schedule_send != null ?
                            `<i class="fas fa-clock" title="Envio agendado para ${ moment(row?.date_schedule_send).format('DD/MM/YYYY HH:mm:ss') }"></i>` :
                            '<i class="fas fa-envelope" title="Envio rápido"></i>'

                        if (row?.status != 'Enviado') {
                            return `<span class="text-warning" title="${row?.message}">${ row?.message ? `${icon} ${ row?.message.substr(0, 30) }...` : ''}</span>`
                        }else{
                            return `<span class="text-success" title="${row?.message}">${ row?.message ? `${icon} ${ row?.message.substr(0, 30) }...` : ''}</span>`
                        }
                    }
                },
                {
                    "targets": 3,
                    "render": function(data, type, row) {
                        return `${row?.user?.username ? row?.user?.username : ''}`
                    }
                },
                {
                    "targets": 4,
                    "render": function(data, type, row) {
                        return `${ row?.status ? row?.status : ''}`
                    }
                },
                {
                    "targets": 5,
                    "render": function(data, type, row) {
                        return `${ row?.created_at ? moment(row?.created_at).format('DD/MM/YY HH:mm') : ''}`
                    }
                },
                {
                    "targets": 6,
                    "render": function(data, type, row) {
                        return `${ row?.send_at ? moment(row?.send_at).format('DD/MM/YY HH:mm') : 'Não enviada'}`
                    }
                },
                {
                    "targets": 7,
                    "render": (data, type, row) => {

                        let btnView = `<a class="btn btn-sm btn-primary" onclick="viewItem('${row?.id}')" > <i class="far fa-eye"></i>  </a>`
                        let tryagain = ``
                        let sendSuccess = ``

                        if (row?.status == 'Enviado') {

                            tryagain = `<a class="btn btn-sm btn-primary" title="Enviar novamente" onclick='sendText("{{auth()->user()->id}}", true, "${row?.id}")' > <i class="fas fa-redo"></i> </a>`
                            sendSuccess = `<a class="btn btn-success btn-sm text-white" title="Envio concluído" disabled> <i class="fas fa-check-double"></i> </a>`

                        } else {
                            sit = `<a class="btn btn-info btn-sm text-white" title="Aguardando envio" disabled> <i class="fas fa-spinner fa-spin"></i> </a>`

                        }

                        let btnDelete = `<a class="btn btn-sm btn-danger" onclick="dropItem('${row?.id}')" > <i class="fas fa-trash-alt"></i> </a>`

                        return `${btnView} ${tryagain ? tryagain : sendSuccess } ${btnDelete || ''}`;

                    }
                }
            ],
            "aoColumns": [
                null,
                null,
                null,
                null,
                null,
                {
                    "width": "120px"
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

        $(".table-responsive #table_id_wrapper .dt-buttons button").css('margin-right', '5px')

        $(".btn-selection-all").on('click', () => {
            $(".btn-selection-none").removeClass("hide");
        })

        $(".btn-selection-none").on('click', () => {
            $(".btn-selection-none").addClass("hide");
        })
    }

    async function viewItem(id) {

        await $.post({
            url: `/painel/message/${id}/show`,
            method: `GET`,
            cache: false,
            beforeSend: function(data) {
                $.LoadingOverlay('show');
            },
            success: function(data) {

                $.LoadingOverlay('hide');
                $("#modalShow").modal('show')
                $(".modal-title").html(data?.name || data?.number);
                $("#message").html(data.message);

            },
            error: function(error) {

                $.LoadingOverlay('hide');
                Swal.fire({
                    class: 'error',
                    icon: 'error',
                    title: `Sem dados`,
                    text: `Não foi possível obter os dados.`,
                })

            },
        });

    }

    async function dropItem(id) {

        $.confirm({
            title: 'Confirmação',
            content: 'Deseja mesmo deletar essa mensagem?',
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
                            url: `/painel/message/${id}/delete`,
                            method: "delete",
                            data: {
                                '_token': $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function(retorno) {
                                tableImported.ajax.reload();
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
