@extends('layouts.app-master')

@section('content')
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

@include('layouts.partials.navbar')

<div class="page-wrapper">

    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="page-title mb-0 p-0">Dispositivos</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('sessions.index') }}"> Sessões </a> </li>
                            <li class="breadcrumb-item active" aria-current="page">Editar sessão</li>
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

            <form method="POST" action="{{ route('sessions.update', $session->id) }}" class="row">
                @method('patch')
                @csrf
                <div class="mb-3 col-6">
                    <label for="server_whatsapp" class="form-label">Servidor MYZAP 2.0</label>
                    <input  type="text" class="form-control" value="{{ $session->server_whatsapp }}" name="server_whatsapp" placeholder="https://servermyzap.com:port" required>

                    @if ($errors->has('server_whatsapp'))
                    <span class="text-danger text-left">{{ $errors->first('server_whatsapp') }}</span>
                    @endif
                </div>

                <div class="mb-3 col-6">
                    <label for="apitoken" class="form-label">Chave da API</label>
                    <input type="text" class="form-control" value="{{ $session->apitoken }}" name="apitoken" placeholder="SUAAPIKEY1234" required>
                    @if ($errors->has('apitoken'))
                    <span class="text-danger text-left">{{ $errors->first('apitoken') }}</span>
                    @endif
                </div>

                <div class="mb-3 col-sm-6">
                    <label for="session_name" class="form-label">Nome da sessão</label>
                    <input type="text" class="form-control" value="{{ $session->session_name }}" name="session_name" placeholder="Samsung S1" required>

                    @if ($errors->has('session_name'))
                    <span class="text-danger text-left">{{ $errors->first('session_name') }}</span>
                    @endif
                </div>

                <div class="mb-3 col-sm-6">
                    <label for="session_key" class="form-label">Chave da sessão</label>
                    <input type="tel" id="session_key" value="{{ $session->session_key }}"  class="form-control" name="session_key" placeholder="ABC1234ABC1234" required>

                    @if ($errors->has('session_key'))
                    <span class="text-danger text-left">{{ $errors->first('session_key') }}</span>
                    @endif
                </div>

                <h4> WebHook integração </h4>
                <hr />

                <div class="mb-3 col-sm-6">
                    <label for="webhook_wh_message" class="form-label">Webhook messages</label>
                    <input type="text" class="form-control" value="{{ $session->webhook_wh_message }}" name="webhook_wh_message" placeholder="https://webhook.site">

                    @if ($errors->has('webhook_wh_message'))
                    <span class="text-danger text-left">{{ $errors->first('webhook_wh_message') }}</span>
                    @endif
                </div>

                <div class="mb-3 col-sm-6">
                    <label for="webhook_wh_connect" class="form-label">Webhook connect state</label>
                    <input type="text" class="form-control" value="{{ $session->webhook_wh_connect }}" name="webhook_wh_connect" placeholder="https://webhook.site">

                    @if ($errors->has('webhook_wh_connect'))
                    <span class="text-danger text-left">{{ $errors->first('webhook_wh_connect') }}</span>
                    @endif
                </div>

                <div class="mb-3 col-sm-6">
                    <label for="webhook_wh_status" class="form-label">Webhook status</label>
                    <input type="text" class="form-control" value="{{ $session->webhook_wh_status }}" name="webhook_wh_status" placeholder="https://webhook.site">

                    @if ($errors->has('webhook_wh_status'))
                    <span class="text-danger text-left">{{ $errors->first('webhook_wh_status') }}</span>
                    @endif
                </div>

                <div class="mb-3 col-sm-6">
                    <label for="webhook_wh_qr_code" class="form-label">Webhook QRCode</label>
                    <input type="text" class="form-control" name="webhook_wh_qr_code" value="{{ $session->webhook_wh_qr_code }}" placeholder="https://webhook.site">

                    @if ($errors->has('webhook_wh_qr_code'))
                    <span class="text-danger text-left">{{ $errors->first('webhook_wh_qr_code') }}</span>
                    @endif
                </div>

                <div class="row">
                    <div class="mb-3 col-sm-6">
                        <button type="submit" class="btn btn-primary w-50 mt-3">Atualizar sessão</button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    @include('layouts.partials.footer')

</div>

@endsection

@section('scripts')
<script>
    $(function() {

        $("#session_key").mask("00000000000000");

    });
</script>
@endsection
