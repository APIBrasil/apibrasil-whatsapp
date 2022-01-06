<header class="topbar" data-navbarbg="skin6">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header" data-logobg="skin6">
            <a class="navbar-brand" style="padding: 0 10px 0 20px !important;" href="{{ route('painel.index') }}">
                <img src="https://media4.giphy.com/media/2mzQwAJ8DSWdWlzjgo/giphy.gif" style="width:20%">
                <span style="padding-top:8px"> <strong class="text-success"> Divulga</strong>Whats<small>.com</small></span>
            </a>
            <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
        </div>
        <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">

            <ul class="navbar-nav me-auto mt-md-0 "></ul>

            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link" href="/painel/sessions">
                        <span class="badge bg-success session_state"></span>
                    </a>
                </li>

                <li class="nav-item">
                    @forelse(\Auth::user()->roles as $role)
                    <a class="nav-link" href="/painel/users">
                        <span class="badge bg-primary"> <i class="fas fa-check"></i> {{ $role->name }}</span>
                    </a>
                    @empty
                    <a class="nav-link" href="https://wa.me/5531995360492">
                        <span class="badge bg-danger"> <i class="fas fa-info-circle"></i> SEM PLANO CONTRATADO </span>
                    @endforelse
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link waves-effect waves-dark" href="/painel/users/{{ \Auth::user()->id }}/edit" >
                        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="user" class="profile-pic me-2">
                        @can('dashboard-infos') {{ \Auth::user()->id }} - @endcan {{ \Auth::user()->username }}
                    </a>
                </li>

            </ul>
        </div>
    </nav>
</header>

<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('painel.index') }}" aria-expanded="false">
                        <i class="me-3 fas fa-home" aria-hidden="true"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                @can('mensagens-grupo')
                <li class="sidebar-item">

                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="me-3 fas fa-object-group" aria-hidden="true"></i>
                        <span class="hide-menu">
                            Divulgação
                        </span>
                    </a>

                    <ul aria-expanded="false" class="collapse first-level">

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('groups.index') }}" aria-expanded="false">
                                <i class="me-3 fas fa-users" aria-hidden="true"></i>
                                <span class="hide-menu">
                                    Meus grupos
                                </span>
                            </a>
                        </li>
                        @can('mensagens-privado')
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('contacts.index') }}" aria-expanded="false">
                                <i class="me-3 fas fa-user" aria-hidden="true"></i>
                                <span class="hide-menu">Meus contatos</span>
                            </a>
                        </li>
                        @endcan
                        <!-- <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('groups.index') }}" aria-expanded="false">
                                <i class="me-3 fas fa-user-clock"></i>
                                <span class="hide-menu">
                                    Campanhas
                                </span>
                            </a>
                        </li> -->
                        <!-- <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('templates.index') }}" aria-expanded="false">
                                <i class="me-3 far fa-object-ungroup"></i>
                                <span class="hide-menu">
                                    Layout mensagens
                                </span>
                            </a>
                        </li> -->
                    </ul>
              </li>
              @endcan

                @can('tags-menu')
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('tags.index') }}" aria-expanded="false">
                        <i class="me-3 fas fa-tags" aria-hidden="true"></i>
                        <span class="hide-menu">Tags marcação</span>
                    </a>
                </li>
                @endcan

                @can('usuarios-todos')
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('users.index') }}" aria-expanded="false">
                        <i class="me-3 fa fa-user" aria-hidden="true"></i>
                        <span class="hide-menu">Meus clientes</span>
                    </a>
                </li>
                @else
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('users.index') }}" aria-expanded="false">
                        <i class="me-3 fa fa-user-edit" aria-hidden="true"></i>
                        <span class="hide-menu">Meu perfil</span>
                    </a>
                </li>
                @endcan

                @can('sessoes-menu')
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('sessions.index') }}" aria-expanded="false">
                        <i class="me-3 fab fa-whatsapp" aria-hidden="true"></i>
                        <span class="hide-menu">Minhas sessões</span>
                    </a>
                </li>
                @endcan

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('message.index') }}" aria-expanded="false">
                        <i class="me-3 fas fa-history" aria-hidden="true"></i>
                        <span class="hide-menu">Históricos envios</span>
                    </a>
                </li>

               <!--  <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#" onclick="Swal.fire('Em breve', '', 'info')" aria-expanded="false">
                        <i class="me-3 far fa-file-code" aria-hidden="true"></i>
                        <span class="hide-menu">Documentações</span>
                    </a>
                </li>
 -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="https://documenter.getpostman.com/view/11074732/UVR7LouU" target="_blank" aria-expanded="false">
                        <i class="me-3 fas fa-code" aria-hidden="true"></i>
                        <span class="hide-menu">Documentação API</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="https://apigratis.com.br" target="_blank" aria-expanded="false">
                        <i class="me-3 fas fa-network-wired" aria-hidden="true"></i>
                        <span class="hide-menu">Patrocinador</span>
                    </a>
                </li>

                <hr />

                @can('seguranca-menu')
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('roles.index') }}" aria-expanded="false">
                        <i class="me-3 fas fa-shield-alt" aria-hidden="true"></i>
                        <span class="hide-menu">Segurança</span>
                    </a>
                </li>
                @endcan

                @can('postagens-menu')
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('posts.index') }}" aria-expanded="false">
                        <i class="me-3 fas fa-newspaper" aria-hidden="true"></i>
                        <span class="hide-menu">Atualizações</span>
                    </a>
                </li>
                @endcan

                @can('logs-menu')
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/painel/logs" aria-expanded="false">
                            <i class="me-3 fas fa-people-carry" aria-hidden="true"></i>
                            <span class="hide-menu">Logs plataforma</span>
                        </a>
                    </li>
                @endcan

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('logout.perform') }}" aria-expanded="false">
                        <i class="me-3 fas fa-sign-out-alt" aria-hidden="true"></i>
                        <span class="hide-menu">Fazer logout</span>
                    </a>
                </li>

            </ul>

        </nav>
    </div>
</aside>
