@extends('layouts.app-master')

@section('content')
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

    @include('layouts.partials.navbar')

    <div class="page-wrapper">

        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="page-title mb-0 p-0">Meu perfil</h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/painel">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Usuário</li>
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
                </div>

                <table class="table table-striped" id="table_id" width="100%">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Username</th>
                            <th scope="col">Ultimo Login</th>
                            <th scope="col">Plano</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>

    </div>

    @include('layouts.partials.footer')

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        tableDefault = $('#table_id').DataTable({
            "lengthMenu": [[10, 25, 50, 100, 200, 300, -1], [10, 25, 50, 100, 200, 300, "Todos"]],
            "aaSorting": [[ 0, "DESC" ]],
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
                {
                    extend:    'selectNone',
                    className: 'hide btn-selection-none',
                    text:      '<i class="fas fa-check"></i>',
                    titleAttr: 'Limpar seleção'
                },
                /* {
                    extend:    'selectAll',
                    className: 'btn-selection-all',
                    text:      '<i class="fas fa-check-double"></i>',
                    titleAttr: 'Selecionar todos'
                }, */
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
            ajax: '/painel/users/datatables',
            "columnDefs": [
                {"targets": 0, "render": function (data, type, row) {
                    return `${ row?.id }`
                }},
                {"targets": 1, "render": function (data, type, row) {
                    return `${ row?.name?.length > 3 ? row?.name : 'Sem nome'}`
                }},
                {"targets": 2, "render": function (data, type, row) {
                    return `${ row?.email?.length > 3 ? row?.email : 'Sem e-mail'}`
                }},
                {"targets": 3, "render": function (data, type, row) {
                    return `${ row?.username ? `<i class="fas fa-user"></i> ${row?.username}` : ''}`
                }},
                {"targets": 4, "render": function (data, type, row) {
                    return `${ moment().diff(row?.last_login, 'days') < 1 ? `${ moment(row?.last_login).format('DD/MM/YYYY HH:mm') } <span class="badge bg-success" title="Login recente">N</span>` : ''}`
                }},
                {"targets": 5, "render": function (data, type, row) {
                    return `${ row?.roles[0]?.name ? `<span class='badge bg-success'> <i class='fas fa-check'></i> ${ row?.roles[0]?.name } </span>` : "<span class='badge bg-danger'> <i class='fas fa-info-circle'></i> SEM PLANO CONTRATADO</span>" }`
                }},
                {"targets": 6, "render":  (data, type, row) => {

                    let btnEdit = `@can('usuarios-edit')<a href="/painel/users/${row?.id}/edit" class="btn btn-info btn-sm"> <i class="fas fa-edit"></i> </a>@endcan`
                    let btnDelete = `@can('usuarios-destroy')<a class="btn btn-sm btn-danger" onclick="dropItem(${row?.id})" > <i class="fas fa-trash-alt"></i> </a>@endcan`

                    return `${btnEdit} ${btnDelete}`;

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



        /* table = $('#table_id').DataTable({
            'paging': false,
            select: true,
            "order": [[ 3, "desc" ]],
        });

        $('#table_id tbody').on('click', 'tr', function() {
            $(this).toggleClass('selected');
            var pos = table.row(this).index();
            var row = table.row(pos).data();
            console.log(row);
        }); */

    });

    async function dropItem(id){

        $.confirm({
            title: 'Confirmação',
            content: 'Deseja mesmo deletar esse usuario?',
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
                        url: `/painel/users/${id}/delete`,
                        method: "delete",
                        data: {
                            '_token': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(retorno){

                            tableDefault.ajax.reload()
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
