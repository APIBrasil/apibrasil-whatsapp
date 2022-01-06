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

                <table class="table table-striped" id="table_id">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Username</th>
                            <th scope="col">Cadastrado</th>
                            <th scope="col">Plano</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ substr($user->name, 0, 15)."..." }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/Y/m H:i:s') }}</td>
                            <td>
                                @forelse($user->roles as $role)
                                <span class="badge bg-success"> <i class="fas fa-check"></i> {{ $role->name }}</span>
                                @empty
                                <span class="badge bg-danger"> <i class="fas fa-info-circle"></i> SEM PLANO CONTRATADO</span>
                                @endforelse
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-sm"> <i class="fas fa-edit"></i> </a>
                                @can('usuarios-deletar')
                                {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                                {!! Form::submit('Deletar', ['class' => 'btn btn-danger btn-sm']) !!}
                                {!! Form::close() !!}
                            </td>
                            @endcan
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {!! $users->links() !!}

            </div>
        </div>

    </div>

    @include('layouts.partials.footer')

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        table = $('#table_id').DataTable({
            'paging': false,
            select: true,
            "order": [[ 3, "desc" ]],
        });

        $('#table_id tbody').on('click', 'tr', function() {
            $(this).toggleClass('selected');
            var pos = table.row(this).index();
            var row = table.row(pos).data();
            console.log(row);
        });

    });
</script>
@endsection
