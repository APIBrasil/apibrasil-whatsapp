@extends('layouts.app-master')

@section('content')
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
    @include('layouts.partials.navbar')

    <div class="page-wrapper">

        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="page-title mb-0 p-0">Dashboard</h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-md-6 col-4 align-self-center">
                    <div class="text-end upgrade-btn">
                        <a href="{{ route('groups.index') }}" class="btn btn-success d-none d-md-inline-block text-white" target="_self"> Divulgar em grupos </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid pt-0">

            <div class="mt-2">
                @include('layouts.partials.messages')

                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-life-ring"></i> Esse sistema está em <strong> versão beta tester</strong> , em caso de problema. Favor enviar para <a href="http://wa.me/5531995360492"> Jonathan </a> </a>
                </div>

                @if( !isset(\Auth::user()->server_whatsapp))
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-cloud"></i> Você precisa configurar um <strong> servidor de whatsapp </strong> para se conectar. <a href="{{ route('users.index', \Auth::user()->id) }}" class="btn btn-sm btn-primary"> Configurar </a>
                </div>
                @endif
            </div>

            <div class="row">

                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="pie-chart-container">
                                <canvas id="pie-chart" style="height:50px !important"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="pie-chart-container">
                                <canvas id="pie-chart-2" style="height:50px !important"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @can('dashboard-geral')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-md-flex">
                                <h4 class="card-title col-md-10 mb-md-0 mb-3 align-self-center">
                                    Movimentação da plataforma
                                </h4>
                            </div>

                            <div class="table-responsive mt-5">
                                <table class="table stylish-table no-wrap">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0" colspan="2">Cliente</th>
                                            <th class="border-top-0">Cadastrado</th>
                                            <th class="border-top-0">Login</th>
                                            <th class="border-top-0">Mensagens</th>
                                            <th class="border-top-0">Contatos</th>
                                            <th class="border-top-0">Sessões</th>
                                            <th class="border-top-0">Grupos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                        <tr>
                                            <td style="width: 50px">
                                                <span class="round">{{ ucfirst($user->username[0]) }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <h6>{{ $user->id }} - {{ $user->username }}</h6>
                                                <small class="text-muted">{{ $user->name }}</small>
                                            </td>
                                            <td class="align-middle">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i:s') }}</td>
                                            <td class="align-middle">{{ \Carbon\Carbon::parse($user->last_login)->format('d/m/Y H:i:s') }}</td>
                                            <td class="align-middle">{{ $user->messages->count() ?? 0 }}</td>
                                            <td class="align-middle">{{ $user->contacts->count() ?? 0 }}</td>
                                            <td class="align-middle">{{ $user->sessions->count() ?? 0 }}</td>
                                            <td class="align-middle">{{ $user->groups->count() ?? 0 }}</td>
                                        </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @endcan
        </div>

        @include('layouts.partials.footer')

    </div>
</div>

@endsection
@section('scripts')

<script>
    $(function() {

        var cData = JSON.parse(`<?php echo $chart_data; ?>`);
        var ctx = $("#pie-chart");

        var data = {
            labels: cData.label,
            datasets: [{
                label: "Total de mensagens por dia",
                data: cData.data,
                backgroundColor: [
                    'transparent',
                ],
                borderColor: 'rgb(75, 192, 192)',
            }]
        };

        var options = {
            responsive: true,
            animations: {
                tension: {
                    duration: 1000,
                    easing: 'line',
                    from: 1,
                    to: 0,
                    loop: true
                }
            },
            legend: {
                display: true,
                position: "bottom",
                labels: {
                    fontColor: "#333",
                    fontSize: 16
                }
            },
            scales: {
                y: { // defining min and max so hiding the dataset does not change scale range
                    min: 0,
                    max: 100
                },
            }
        };

        //create line Chart class object
        var chart1 = new Chart(ctx, {
            type: "line",
            data: data,
            options: options
        });

        ////////////////////////////////////////////////////////////////////////
        var cData = JSON.parse(`<?php echo $chart_data2; ?>`);
        var ctx = $("#pie-chart-2");

        var data = {
            labels: cData.label,
            datasets: [{
                label: "Total de mensagens não enviadas",
                data: cData.data,
                backgroundColor: [
                    'transparent',
                ],
                borderColor: '#FF0000',
            }]
        };

        var options = {
            responsive: true,
            animations: {
                tension: {
                    duration: 1000,
                    easing: 'line',
                    from: 1,
                    to: 0,
                    loop: true
                }
            },
            legend: {
                display: true,
                position: "bottom",
                labels: {
                    fontColor: "#333",
                    fontSize: 16
                }
            },
            scales: {
                y: { // defining min and max so hiding the dataset does not change scale range
                    min: 0,
                    max: 100
                },
            }
        };

        //create line Chart class object
        var chart1 = new Chart(ctx, {
            type: "line",
            data: data,
            options: options
        });

    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
@endsection
