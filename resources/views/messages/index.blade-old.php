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
                </div>

                <table class="table table-striped" id="table_id">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Nome</th>
                            <th>Mensagem</th>
                            <th>Responsável</th>
                            <th>Situação</th>
                            <th>Enviada</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $key => $item)
                        <tr>
                            <td> {{ $item->type }}</td>
                            <td>
                                @if( isset($item->group) )
                                    <span class="badge bg-info" tile="Grupo">G</span> - {{ $item->group->name ? $item->group->name :  $item->number }}</td>
                                @elseif( isset($item->contact) )
                                    <span class="badge bg-info" tile="Contato">C</span> - {{ $item->contact->name ? $item->contact->name : $item->number }}
                                @else
                                    <span class="badge bg-info" tile="Outros">O</span> - {{ $item->number }}
                                @endif
                            </td>
                            <td> {{ isset($item->message) ? substr($item->message, 0, 15)."..." : "--" }} </td>
                            <td> {{ $item->user->username }}</td>
                            <td> {{ $item->status }}</td>
                            <td> {{ isset($item->send_at) ? \Carbon\Carbon::parse($item->send_at)->format('d/m/Y H:i:s') : '00/00/0000 00:00:00' }}</td>
                            <td>

                                <a class="btn btn-sm btn-primary" onclick='viewItem("{{$item->id}}")' > <i class="far fa-eye"></i>  </a>

                                @if($item->status == 'Enviado')
                                    <a class="btn btn-sm btn-primary" title="Enviar novamente" onclick='sendText("{{$item->user_id}}", true, "{{$item->id}}")' > <i class="fas fa-redo"></i> </a>
                                    <a class="btn btn-success btn-sm text-white" title="Envio concluído" disabled> <i class="fas fa-check-double"></i> </a>
                                @else
                                    <a class="btn btn-info btn-sm text-white" title="Aguardando envio" disabled> <i class="fas fa-spinner fa-spin"></i> </a>

                                    {!! Form::open(['method' => 'DELETE','route' => ['message.destroy', $item->id],'style'=>'display:inline']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                    {!! Form::close() !!}
                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $messages->links() !!}

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
    $((document) => {

        table = $('#table_id').DataTable({
            'paging': false,
            "order": [[ 6, "desc" ]],
        });

        $('#table_id tbody').on('click', 'tr', function() {
            $(this).toggleClass('selected');
            var pos = table.row(this).index();
            var row = table.row(pos).data();
        });

    })

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

</script>

@endsection
