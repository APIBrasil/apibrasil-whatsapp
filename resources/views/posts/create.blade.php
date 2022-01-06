@extends('layouts.app-master')

@section('content')
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

    @include('layouts.partials.navbar')

    <div class="page-wrapper">

        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="page-title mb-0 p-0">Atualizações</h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/painel/posts">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Cadastrar postagem</li>
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

                <form method="POST" action="{{ route('posts.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input value="{{ old('title') }}"
                            type="text"
                            class="form-control"
                            name="title"
                            placeholder="" required>

                        @if ($errors->has('title'))
                            <span class="text-danger text-left">{{ $errors->first('title') }}</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="body" class="form-label">Introdução</label>
                        <input value="{{ old('body') }}"
                            type="text"
                            class="form-control"
                            name="body"
                            placeholder="" required>

                        @if ($errors->has('body'))
                            <span class="text-danger text-left">{{ $errors->first('body') }}</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea
                            type="text"
                            class="form-control"
                            name="description"
                            rows="10"
                            placeholder="" required>{{ old('description') }}</textarea>

                        @if ($errors->has('description'))
                            <span class="text-danger text-left">{{ $errors->first('description') }}</span>
                        @endif
                    </div>

                    <a href="{{ route('posts.index') }}" class="btn btn-danger">Voltar</a>
                    <button type="submit" class="btn btn-success">Salvar</button>

                </form>

            </div>
        </div>

    </div>

    @include('layouts.partials.footer')

</div>

@endsection
