<nav class="navbar navbar-default navbar-fixed-top navbar-inverse main-menu">
  <div class="container-fluid">
    <div class="navbar-header">
        <div class="navbar-brand">
        @if(Auth::check())
        <button type="button" class="hamburger is-closed" data-toggle="offcanvas">
            <span class="hamb-top"></span>
            <span class="hamb-middle"></span>
            <span class="hamb-bottom"></span>
        </button>
        @endif
        <a href="{{route('home')}}">{{Config::get('app.app_name')}}</a>
        </div>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
        @if(Auth::check())
        <li><a href="{{route('admin.home')}}" title="Home"><i class="material-icons">home</i></a></li>
        <li><a href="#" class="notifications" title="Notificações"><i class="material-icons">notifications</i></a></li>
        <li><a href="{{route('admin.user.profile')}}" title="Perfil"><i class="material-icons">person</i></a></li>
        <li role="separator" class="divider"></li>
        <li><a href="{{route('test.logout')}}" title="Sair" class="margin-right-10"><i class="material-icons">exit_to_app</i></a></li>
        @else
        <li><a href="{{route('test.users')}}">Usuários</a></li>
        <li><a href="{{route('test.projects')}}">Projetos</a></li>
        @endif
      </ul>
    </div>
  </div>
</nav>