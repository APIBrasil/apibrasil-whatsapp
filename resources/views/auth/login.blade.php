<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Primary Meta Tags -->
    <title>Faça login - Provedor de API não Oficial do WhatsApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="Swipe - Mobile App One Page Bootstrap 5 Template">
    <meta name="author" content="Themesberg">
    <meta name="description" content="Free Mobile Application One Page Bootstrap 5 Template by Themesberg">
    <meta name="keywords" content="bootstrap, bootstrap 5, bootstrap 5 one page, bootstrap 5 mobile application, one page template, bootstrap 5 one page template, themesberg, themesberg one page, one page template bootstrap 5" />
    <link rel="canonical" href="https://themesberg.com/product/bootstrap/swipe-free-mobile-app-one-page-bootstrap-5-template">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://demo.themesberg.com/swipe/">
    <meta property="og:title" content="Swipe - Mobile App One Page Bootstrap 5 Template">
    <meta property="og:description" content="Free Mobile Application One Page Bootstrap 5 Template by Themesberg">
    <meta property="og:image" content="https://themesberg.s3.us-east-2.amazonaws.com/public/products/swipe/swipe-thumbnail.jpg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://demo.themesberg.com/swipe/">
    <meta property="twitter:title" content="Swipe - Mobile App One Page Bootstrap 5 Template">
    <meta property="twitter:description" content="Free Mobile Application One Page Bootstrap 5 Template by Themesberg">
    <meta property="twitter:image" content="https://themesberg.s3.us-east-2.amazonaws.com/public/products/swipe/swipe-thumbnail.jpg">

    <!-- Favicon -->
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

    <!-- NOTICE: You can use the _analytics.html partial to include production code specific code & trackers -->

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
                                        <h1 class="mb-0 h3">Faça login na plataforma</h1>
                                    </div>
                                    <form method="post" action="{{ route('login.perform') }}">
                                        <!-- Form -->
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                                        <div class="form-group mb-4">
                                            <label for="exampleInputEmailCard1">E-mail</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1"><span class="fas fa-envelope"></span></span>
                                                <input type="text" class="form-control" placeholder="" name="username" value="{{ old('username') }}" required="required" autofocus>
                                                @if($errors)
                                                    <span class="text-danger row">{{ $errors->first('email') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- End of Form -->
                                        <div class="form-group">
                                            <!-- Form -->
                                            <div class="form-group mb-4">
                                                <label for="exampleInputPasswordCard1">Sua senha</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon2"><span class="fas fa-unlock-alt"></span></span>
                                                    <input type="password" placeholder="******" class="form-control" name="password" value="{{ old('password') }}" required="required" />
                                                    @if($errors)
                                                        <span class="text-danger row">{{ $errors->first('password') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- End of Form -->
                                            <div class="d-block d-sm-flex justify-content-between align-items-center mb-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" name="remember" id="remember">
                                                    <label class="form-check-label" for="remember">
                                                        Relembrar
                                                    </label>
                                                </div>
                                                <div><a href="#" class="small text-right">Esqueceu a senha?</a></div>
                                            </div>
                                            <div class="mt-2">
                                                @include('layouts.partials.messages')
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-block btn-primary">Fazer login</button>
                                        <a class="btn btn-block btn-secondary" href="{{ route('home.index') }}" >Voltar</a>
                                    </form>
                                    <div class="d-flex justify-content-center align-items-center mt-4">
                                        <span class="font-weight-normal">
                                            Não é registrado?
                                            <a href="/register" class="font-weight-bold">Criar uma nova conta</a>
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
