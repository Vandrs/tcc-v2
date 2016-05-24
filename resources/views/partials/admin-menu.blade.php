<ul class="sidebar-nav text-left">
    <li class="sidebar-brand">
        <i class="glyphicon glyphicon-resize-horizontal"></i> <span class="admin-menu-text">Menu</span>
    </li>
    <li>
        <a href="{{route('admin.home')}}">
            <i class="glyphicon glyphicon-home"></i> <span class="admin-menu-text">Home</span>
        </a>
    </li>
    <li>
        <a href="{{route('admin.project.create')}}">
            <i class="glyphicon glyphicon-file"></i> <span class="admin-menu-text">Novo Projeto</span>
        </a>
    </li>
    <li>
        <a href="{{route('admin.user.projects')}}">
            <i class="glyphicon glyphicon-list-alt"></i> <span class="admin-menu-text">Meus projetos</span>
        </a>
    </li>
    <li>
        <a href="{{route('search')}}">
            <i class="glyphicon glyphicon-search"></i> <span class="admin-menu-text">Encontrar projetos</span>
        </a>
    </li>
    <li>
        <a href="{{route('admin.user.profile')}}">
            <i class="glyphicon glyphicon-user"></i> <span class="admin-menu-text">Perfil</span>
        </a>
    </li>

</ul>