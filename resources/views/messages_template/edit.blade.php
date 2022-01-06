@extends('layouts.app-master')

@section('content')
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

    @include('layouts.partials.navbar')

    <div class="page-wrapper">

        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="page-title mb-0 p-0">Editar contato</h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('contacts.index') }}">Contato</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Editar contato</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card row pb-3">
                <form  method="POST" action="{{ route('contacts.update', $contacts->id) }}" method="patch" class="row">
                    @method('patch')
                    @csrf

                    <div class="mt-2">
                        @include('layouts.partials.messages')
                    </div>

                    <div class="mb-3 col-6">
                        <label for="name" class="form-label">Nome</label>
                        <input value="{{ $contacts->name }}" type="text" class="form-control" id="name" name="name" placeholder="" required>

                        @if ($errors->has('name'))
                        <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <div class="mb-3 col-6">
                        <label for="phone" class="form-label">WhatsApp</label>
                        <input value="{{ $contacts->phone }}" type="text" class="form-control" id="phone" name="phone" placeholder="+5500000000000" required>

                        @if ($errors->has('phone'))
                        <span class="text-danger text-left">{{ $errors->first('phone') }}</span>
                        @endif
                    </div>

                    <div class="mb-3 col-6">
                        <label for="group_id" class="form-label">Grupo do WhatsApp</label>
                        <select class="form-control" name="group_id" id="group_id">
                            <option value="">Selecione</option>
                            @forelse ($groups as $item)
                            @if(isset($item->subject)) <option value="{{$item->id}}" @if($contacts->group_id == $item->id) selected @endif > {{ $item->subject }} </option> @endif
                            @empty
                            @endforelse
                        </select>

                        @if ($errors->has('name'))
                        <span class="text-danger text-left">{{ $errors->first('isBusiness') }}</span>
                        @endif
                    </div>

                    <div class="mb-3 col-3">
                        <label for="isBusiness" class="form-label">Tipo de WhatsApp</label>
                        <select class="form-control" name="isBusiness" id="isBusiness">
                            <option value="">Selecione</option>
                            <option value="1" @if($contacts->isBusiness == 1) selected @endif >WhatsApp Business</option>
                            <option value="0" @if($contacts->isBusiness == 0) selected @endif >WhatsApp Comum</option>
                        </select>

                        @if ($errors->has('name'))
                        <span class="text-danger text-left">{{ $errors->first('isBusiness') }}</span>
                        @endif
                    </div>

                    <div class="mb-3 col-3">
                        <label for="tag" class="form-label">Tag</label>
                        <select class="form-control" name="tag_id" id="tag_id">
                            <option value="">Selecione</option>
                            @forelse ($tags as $item)
                            <option value="{{ $item->id }}" @if($contacts->tag_id == $item->id) selected @endif > {{ $item->name }}</option>
                            @empty
                            @endforelse
                        </select>

                        @if ($errors->has('tag'))
                        <span class="text-danger text-left">{{ $errors->first('tag') }}</span>
                        @endif
                    </div>

                    <div class="row">

                        <div class="mb-3 col-6">
                            <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Voltar</a>
                            <button type="submit" class="btn btn-primary">Salvar</button>
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

        $("#phone").mask("+0000000000000");

    });
</script>
@endsection
