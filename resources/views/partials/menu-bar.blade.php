<nav class="navbar navbar-default main-menu">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Navegação</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="{{route('home')}}">{{Config::get('app.app_name')}}</a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="{{route('test.users')}}">Usuários</a></li>
        <li><a href="{{route('test.projects')}}">Projetos</a></li>
        @if(Auth::check())
        <li><a href="{{route('user.view',['id' => Auth::user()->id])}}">{{Auth::user()->name}} (Perfil)</a></li>
        <li role="separator" class="divider"></li>
        <li><a href="{{route('test.logout')}}">Logout</a></li>
        @endif
      </ul>
    </div>
  </div>
</nav>