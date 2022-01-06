@extends('layouts.app-master')

@section('content')
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

    @include('layouts.partials.navbar')

    <div class="page-wrapper">

        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="page-title mb-0 p-0">Editar grupo</h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('groups.index') }}">Grupos</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Editar contato</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card row pb-3">
                <form  method="POST" action="{{ route('groups.update', $group->id) }}" method="patch" id="formSubmit" class="row" enctype="multipart/form-data">
                    @method('patch')
                    @csrf

                    <div class="mt-2">
                        @include('layouts.partials.messages')
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="avatar" class="col-form-label">Foto do grupo</label>
                            <input type="file" class="form-control" id="avatar" name="avatar" />
                        </div>
                    </div>

                    <div class="mb-3 col-3">
                        <label for="name" class="form-label">Nome</label>
                        <input value="{{ $group->name }}" type="text" class="form-control" id="name" name="name" placeholder="" required>

                        @if ($errors->has('name'))
                        <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <div class="mb-3 col-3">
                        <label for="group_id" class="form-label">Id do grupo</label>
                        <input value="{{ $group->group_id }}" type="text" class="form-control" id="group_id" name="group_id" required>

                        @if ($errors->has('group_id'))
                        <span class="text-danger text-left">{{ $errors->first('group_id') }}</span>
                        @endif
                    </div>

                    <div class="mb-3 col-3">
                        <label for="subject" class="form-label">Assunto</label>
                        <input value="{{ $group->subject }}" type="text" class="form-control" id="subject" name="subject" required>

                        @if ($errors->has('subject'))
                        <span class="text-danger text-left">{{ $errors->first('subject') }}</span>
                        @endif
                    </div>

                    <div class="mb-3 col-3">
                        <label for="tag" class="form-label">Tag</label>
                        <select class="form-control" name="tag_id" id="tag_id">
                            <option value="">Selecione</option>
                            @forelse ($tags as $item)
                            <option value="{{ $item->id }}" @if($group->tag_id == $item->id) selected @endif > {{ $item->name }}</option>
                            @empty
                            @endforelse
                        </select>

                        @if ($errors->has('tag'))
                        <span class="text-danger text-left">{{ $errors->first('tag') }}</span>
                        @endif
                    </div>

                    <div class="mb-3 col-6">
                        <label for="link" class="form-label">Link</label>
                        <input value="{{ $group->link }}" type="text" class="form-control" id="link" name="link" readonly>

                        @if ($errors->has('link'))
                        <span class="text-danger text-left">{{ $errors->first('link') }}</span>
                        @endif
                    </div>

                    <div class="mb-3 col-6">
                        <label for="desc" class="form-label">Descrição</label>
                        <input value="{{ $group->desc }}" type="text" class="form-control" id="desc" name="desc" required>

                        @if ($errors->has('desc'))
                        <span class="text-danger text-left">{{ $errors->first('desc') }}</span>
                        @endif
                    </div>

                    <div class="row">

                        <div class="mb-3 col-6">
                            <a href="{{ route('groups.index') }}" class="btn btn-secondary">Voltar</a>
                            <button class="btn btn-primary" onclick="$(this).html('Editando...').attr('disabled', true); setTimeout( () => $(this).html('Editar').attr('disabled', false), 30000);$('#formSubmit').submit()">Editar</button>
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

        $("#phone").mask("5500000000000");

    });
</script>
@endsection
