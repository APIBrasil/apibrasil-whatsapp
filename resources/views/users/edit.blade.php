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
                                <li class="breadcrumb-item"><a href="#">Meu perfil</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Editar usuário</li>
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

                <form method="post" class="row" action="{{ route('users.update', $user->id) }}">
                    @method('patch')
                    @csrf
                    <div class="mb-3 col-3">
                        <label for="name" class="form-label">Nome</label>
                        <input value="{{ $user->name }}" type="text" class="form-control" name="name" placeholder="Name" required>

                        @if ($errors->has('name'))
                        <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="mb-3 col-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input value="{{ $user->email }}" type="email" class="form-control" name="email" placeholder="Email address" required>
                        @if ($errors->has('email'))
                        <span class="text-danger text-left">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    <div class="mb-3  col-3">
                        <label for="cellphone" class="form-label">Celular</label>
                        <input value="{{ $user->cellphone }}" type="text" class="form-control" id="cellphone" name="cellphone" placeholder="">
                        @if ($errors->has('cellphone'))
                        <span class="text-danger text-left">{{ $errors->first('cellphone') }}</span>
                        @endif
                    </div>

                    <div class="mb-3 col-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input value="{{ $user->cpf }}" type="text" class="form-control" id="cpf" name="cpf" placeholder="">
                        @if ($errors->has('cpf'))
                        <span class="text-danger text-left">{{ $errors->first('cpf') }}</span>
                        @endif
                    </div>

                    <div class="mb-3 col-3">
                        <label for="username" class="form-label">Usuário</label>
                        <input value="{{ $user->username }}" type="text" class="form-control" name="username" placeholder="Usuário" required>
                        @if ($errors->has('username'))
                        <span class="text-danger text-left">{{ $errors->first('username') }}</span>
                        @endif
                    </div>

                    <div class="mb-3  col-3">
                        <label for="block_mail" class="form-label">Desligar aviso e-mail</label>
                        <select class="form-control" name="block_mail" id="block_mail">
                            <option value="0"  @if($user->block_mail == 1) selected @endif>Não</option>
                            <option value="1"  @if($user->block_mail == 1) selected @endif>Sim</option>
                        </select>
                        @if ($errors->has('block_mail'))
                        <span class="text-danger text-left">{{ $errors->first('block_mail') }}</span>
                        @endif
                    </div>

                    @if($roles->count() >= 1)
                    <div class="mb-3  col-3">
                        <label for="role" class="form-label">Plano</label>
                        <select class="form-control" name="role">
                            @if($roles->count() > 1) <option value="">Selecione o grupo</option>@endif
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ in_array($role->name, $userRole)
                                        ? 'selected'
                                        : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('role'))
                        <span class="text-danger text-left">{{ $errors->first('role') }}</span>
                        @endif
                    </div>
                    @endif

                    <div class="row">

                        <div class="mb-3  col-6">
                            <button type="submit" class="btn btn-primary w-50">Atualizar</button>
                        </div>

                    </div>
                </form>

            </div>
        </div>

    </div>

    @include('layouts.partials.footer')

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        $("#phone").mask("+00000000000000");
        $("#cpf").mask("000.000.000-00");

        table = $('#table_id').DataTable({
            'paging': false,
            select: true,
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
