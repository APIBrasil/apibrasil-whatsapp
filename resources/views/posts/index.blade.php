@extends('layouts.app-master')

@section('content')
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
    @include('layouts.partials.navbar')

    <div class="page-wrapper">

        <div class="container-fluid">

            <div class="page-breadcrumb" style="padding-left:0px !important;">
                <div class="row align-items-center">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="page-title mb-0 p-0">Atualizações</h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/painel">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Atualizações do sistema</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="col-md-6 col-4 align-self-right">
                        @can('postagem-adicionar') <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm float-end"> Novo post</a>@endcan
                    </div>
                </div>
            </div>

            <div class="row justify-content-left">

                @foreach ($posts as $item)
                <div class="col-lg-3 col-md-3">
                    <div class="card">
                        <img class="card-img-top img-responsive" src="https://www.cozilandia.com.br/doutor/uploads/2/blog/2020/12/blog-estante-em-aluminio-novidade-na-cozilandia-6db4f72183.png" alt="Card">
                        <div class="card-body">
                            <ul class="list-inline d-flex align-items-center">
                                <li class="ps-0">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</li>
                                <li class="ms-auto"><a href="javascript:void(0)" class="link">{{ $item->user->name ?? '' }}</a></li>
                            </ul>
                            <h3 class="font-normal">{{ $item->title }}</h3>
                            <p class="font-normal">{{ isset($item->description) ? substr($item->description, 0, 115)."..." : 'Sem descrição' }}</p>

                        </div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('posts.show', $item->id) }}" class="float-end"> <i class="fas fa-eye"></i> Ler mais... </a>
                                @can('postagem-editar')<a href="{{ route('posts.edit', $item->id) }}" class="float-end"> Editar </a>@endcan
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>

        </div>

        <footer class="footer text-center">
            © {{ date('Y') }} Powereby <a href="https://www.apibrasil.com.br/">APIBrasil</a>
        </footer>

    </div>
</div>

@endsection
@section('scripts')

@endsection
