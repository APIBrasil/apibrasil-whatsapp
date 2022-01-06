@extends('layouts.app-master')

@section('content')
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
    @include('layouts.partials.navbar')

    <div class="page-wrapper">

        <div class="container-fluid">

            <div class="page-breadcrumb" style="padding-left:0px !important;">
                <div class="row align-items-center">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="page-title mb-0 p-0">{{ $post->title }}</h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/painel/posts">Atualizações</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Visualizar</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-left">

            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-inline d-flex align-items-center">
                            <li class="ps-0">{{ \Carbon\Carbon::parse($post->created_at)->format('d/m/Y H:i') }}</li>
                            <li class="ms-auto"><a href="javascript:void(0)" class="link"> Por <strong> {{ $post->user->name ?? '' }}</strong> </a></li>
                        </ul>
                        <h2 class="font-normal">{{ $post->title }}</h2>
                        <hr />
                        <h3 class="font-normal">{{ $post->body }}</h3>
                        <p class="font-normal">{!! $post->description !!}</p>

                    </div>


                </div>
            </div>

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
