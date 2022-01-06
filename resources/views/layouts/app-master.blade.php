<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="envio de mensagens em massa, whatsapp api nao oficial, whatsapp api, php, webhook api whatsapp, api integracao whatsapp, integracao whatsapp">
    <meta name="description" content="DivulgaWhatsApp, é um provedor de envio de mensagens por whatsapp via API ou painel, agendamento de envios e muito mais...">
    <meta name="robots" content="noindex,nofollow">
    <title>Painel de Controle - DivulgaWhatsApp</title>
    <link rel="canonical" href="" />

    <link href="{!! url('assets/bootstrap/css/bootstrap.min.css') !!}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('assets/plugins/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">

    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/gif" sizes="32x32" href="{{ asset('assets/img/favicon/favicon.gif') }}">
    <link rel="icon" type="image/gif" sizes="16x16" href="{{ asset('assets/img/favicon/favicon.gif') }}">
    <link href="{{ asset('css/style_backend.min.css') }}" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}" value="{{ csrf_token() }}" />

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="{{ asset('css/jquery-confirm.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/buttons.dataTables.min.css') }}" />

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
    .pagination > li > a, .pagination > li > span{
        color: #000;
        padding-left: 5px;
        padding-right: 5px;
        border-radius: 5px;
    }
    .pagination > li.active > a, .pagination > li.active > span{
        background-color: #fff;
        color: #000;
        padding-left: 5px;
        padding-right: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    </style>

</head>

<body>

    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    @yield('content')

    <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/plugins/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('js/waves.js') }}"></script>
    <script src="{{ asset('js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot.tooltip/js/jquery.flot.tooltip.min.js') }}"></script>
    <script src="{{ asset('js/pages/dashboards/dashboard1.js') }}"></script>

    <script src="{!! url('assets/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>

    <script src="//igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>
    <script src="//app.agibens.com/assets/js/loadingoverlay.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.4.0/socket.io.min.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://momentjs.com/downloads/moment.min.js"></script>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">

    <script>

    $(() => {

        file64 = '';

        $("#file").on('change', async function (e) {

            let reader = new FileReader();
            let file = await $("#file")[0].files[0]

            await reader.readAsDataURL(file);

            reader.onload = () => {
                file64 = reader.result;
            };
        })

        url = "{{ Auth::user()->server_whatsapp }}"
        socket = io(url);

        socket.on(`events`, (events) => {
            $(".session_state").html(`<span title="Situação da Sessão"> <i class="fas fa-mobile"></i> ${events?.state || 'Desconectado'} </span>`)
            console.log(events)
        })

        socket.on(`send-message`, (message) => {
            $(".session_state").html(`<span title="Status da última mensagem"> <i class="far fa-envelope"></i> ${message?.status || ''} </span>`)
            console.log(message)
        })

        setTimeout(() => {
            $(".session_state").html(``)
        }, 10000)

    })

    async function sendText(user_id, again, message_id) {

        if( $("#message").val() == "" && again == false) {
            Swal.fire({
                class: 'error',
                icon: 'error',
                title: 'Oops...',
                text: `Digite um texto para enviar.`,
                footer: '<a href="https://wa.me/5531995360492" target="_blank">Precisa de ajuda?</a>'
            })
            return false;
        }

        tag_id = $("select[name='tag_id'] option:selected").val();

        let schedule = $("select[id='schedule'] option:selected").val();
        let date_schedule_send = $("input[name='date_schedule_send']").val();

        if(schedule == "sim" && date_schedule_send == "") {
            Swal.fire({
                class: 'error',
                icon: 'error',
                title: `Uai...`,
                text: `Selecione um horário.`,
            })
            return false;
        }

        await $.post({
            url: `/painel/message/sendText`,
            method: `POST`,
            data: {
                "tag_id" : tag_id || '',
                "again": again || false,
                "message_id": message_id || '',
                "file" : file64 || '',
                "schedule": `${schedule ? schedule : 'false'}`,
                "date_schedule_send": `${date_schedule_send || ''}`,
                "_token": "{{ csrf_token() }}",
                "user_id": `${user_id || ''}`,
                "message": $("#message").val() || '',
                "number": $("#group_id").val() != '' ? $("#group_id").val() : $("#number").val() ,
            },
            beforeSend: function(data) {
                $.LoadingOverlay('show');
            },
            success: function(data) {

                if(data){

                    Swal.fire({
                        icon: 'success',
                        title: 'Nice!',
                        text: `${again == true ? 'Sua mensagem será enviada novamente em breve, aguarde' : data?.message}`,
                    })

                    $("#message").val('');
                    $("#file").val('');
                }
                $.LoadingOverlay('hide');

            },
            error: function(error) {

                if(typeof error != 'undefined')
                {
                    error = JSON.parse(error?.responseText);
                    Swal.fire({
                        class: 'error',
                        icon: 'error',
                        title: 'Oops...',
                        text: `${error?.message?.message || 'Erro ao enviar mensagem, verifique o status do servidor ou tente novamente mais tarde.'}`,
                        footer: '<a href="https://wa.me/5531995360492" target="_blank">Precisa de ajuda?</a>'
                    })
                }
                $.LoadingOverlay('hide');
            },
        });

    }

    </script>

    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/jszip.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script src="{{ asset('js/jquery-confirm.min.js') }}"></script>

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/61b7e5c5c82c976b71c14302/1fmr5j3bl';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->

    @section("scripts")

    @show
  </body>
</html>
