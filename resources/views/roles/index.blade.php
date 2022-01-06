@extends('layouts.app-master')

@section('content')
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

    @include('layouts.partials.navbar')

    <div class="page-wrapper">

        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="page-title mb-0 p-0">Segurança do sistema</h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/painel">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Grupos</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="col-md-6 col-4 align-self-center">

                    <div class="text-end upgrade-btn">
                        @can('seguranca-criar')<a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm float-right"> Novo grupo </a>@endcan
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
                            <th width="1%">No</th>
                            <th>Nome</th>
                            <th>Guard</th>
                            <th>Criado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $key => $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->guard_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($role->created_at)->format('d/m/Y H:i:s') }}</td>
                            <td>

                                @can('seguranca-editar')
                                <a class="btn btn-primary btn-sm" href="{{ route('roles.edit', $role->id) }}"> <i class="fas fa-edit"></i> </a>
                                @endcan

                                @can('seguranca-deletar')
                                {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                {!! Form::close() !!}
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {!! $roles->links() !!}

            </div>
        </div>

    </div>

    @include('layouts.partials.footer')

</div>

@endsection

@section('scripts')
<script>
    $((document) => {

        table = $('#table_id').DataTable({
            'paging': false,
        });

    })
</script>

@endsection
