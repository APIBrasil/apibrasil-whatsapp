<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Primary Meta Tags -->
    <title>Cadastre-se - Provedor de API não Oficial do WhatsApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="Swipe - Mobile App One Page Bootstrap 5 Template">
    <meta name="author" content="Themesberg">
    <meta name="description" content="Free Mobile Application One Page Bootstrap 5 Template by Themesberg">
    <meta name="keywords" content="bootstrap, bootstrap 5, bootstrap 5 one page, bootstrap 5 mobile application, one page template, bootstrap 5 one page template, themesberg, themesberg one page, one page template bootstrap 5" />
    <link rel="canonical" href="">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="#">
    <meta property="og:title" content="Swipe - Mobile App One Page Bootstrap 5 Template">
    <meta property="og:description" content="Free Mobile Application One Page Bootstrap 5 Template by Themesberg">
    <meta property="og:image" content="">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="#">
    <meta property="twitter:title" content="Swipe - Mobile App One Page Bootstrap 5 Template">
    <meta property="twitter:description" content="Free Mobile Application One Page Bootstrap 5 Template by Themesberg">
    <meta property="twitter:image" content="">


    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/gif" sizes="32x32" href="{{ asset('assets/img/favicon/favicon.gif') }}">
    <link rel="icon" type="image/gif" sizes="16x16" href="{{ asset('assets/img/favicon/favicon.gif') }}">

    <link rel="manifest" href="./assets/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="./assets/img/favicon/safari-pinned-tab.svg" color="#ffffff">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Fontawesome -->
    <link type="text/css" href="./vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!-- Swipe CSS -->
    <link type="text/css" href="./css/style.css" rel="stylesheet">

</head>

<body>
    <main>
        <!-- Section -->
        <section class="min-vh-100 d-flex align-items-center bg-soft">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="signin-inner mt-3 mt-lg-0 bg-white shadow border rounded border-light w-100">
                            <div class="row gx-0 align-items-center justify-content-between">
                                <div class="col-12 col-lg-5 d-none d-lg-block rounded-left bg-secondary">
                                    <img src="./assets/img/illustrations/login.svg" alt="login image">
                                </div>
                                <div class="col-12 col-lg-7 px-3 py-5 px-sm-5 px-md-6">
                                    <div class="text-center text-md-center mb-4 mt-md-0">
                                        <h1 class="mb-0 h3">Criar uma nova conta</h1>
                                    </div>
                                    <form method="post" action="{{ route('register.perform') }}">
                                        <!-- Form -->
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <div class="form-group mb-2">
                                            <label for="exampleInputEmailCard3">Nome completo</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon3"><span class="fas fa-list"></span></span>
                                                <input type="text" class="form-control" placeholder="" id="name" name="name" value="{{ old('name') }}" required="required" autofocus>
                                                @if($errors)
                                                    <span class="text-danger row">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="exampleInputEmailCard3">E-mail</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon3"><span class="fas fa-envelope"></span></span>
                                                <input type="email" class="form-control" placeholder="" name="email" value="{{ old('email') }}" required="required" autofocus>
                                                @if($errors)
                                                    <span class="text-danger row">{{ $errors->first('email') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="exampleInputEmailCard3">Username</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon3"><span class="fas fa-user"></span></span>
                                                <input type="text" class="form-control" placeholder="" name="username" value="{{ old('username') }}" required="required" autofocus>
                                                @if($errors)
                                                    <span class="text-danger row">{{ $errors->first('username') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- End of Form -->
                                        <div class="form-group row">
                                            <!-- Form -->
                                            <div class="form-group mb-2 col-md-6">
                                                <label for="exampleInputPasswordCard4">Senha</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon4"><span class="fas fa-unlock-alt"></span></span>
                                                    <input type="password" placeholder="" class="form-control" name="password" value="{{ old('password') }}" required="required">
                                                    @if($errors)
                                                        <span class="text-danger row" >{{ $errors->first('password') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- End of Form -->
                                            <!-- Form -->
                                            <div class="form-group mb-2 col-md-6">
                                                <label for="exampleInputPasswordCard4">Confirma senha</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon5"><span class="fas fa-unlock-alt"></span></span>
                                                    <input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="" required="required">
                                                    @if($errors)
                                                        <span class="text-danger row">{{ $errors->first('password_confirmation') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- End of Form -->
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="checkbox" value="" name="lgpd_accept" id="lgpd_accept" required="required">
                                                <label class="form-check-label" for="lgpd_accept">
                                                    Eu aceito as regras do serviço <a href="#" class="text-primary font-weight-bold"> termos e condições</a>
                                                </label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-block btn-primary">Cadastrar agora</button>
                                        <a class="btn btn-block btn-secondary" href="{{ route('home.index') }}">Voltar</a>
                                    </form>

                                    <div class="d-flex justify-content-center align-items-center mt-1">
                                        <span class="font-weight-normal">
                                            Já tem uma conta?
                                            <a href="/login" class="font-weight-bold">Faça login aqui</a>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Core -->
    <script src="./vendor/popper.js/dist/umd/popper.min.js"></script>
    <script src="./vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="./vendor/headroom.js/dist/headroom.min.js"></script>

    <!-- Vendor JS -->
    <script src="./vendor/onscreen/dist/on-screen.umd.min.js"></script>
    <script src="./vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- Swipe JS -->
    <script src="./assets/js/swipe.js"></script>

</body>

</html>
