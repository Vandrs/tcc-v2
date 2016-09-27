
@can(Capabilities::MANAGE_PROJECT, $project)
<a href="{{route('admin.project.management',['id' => $project->id])}}" class="btn btn-info btn-raised" data-toggle="tooltip" title="Gerenciar Projeto">
	<i class="material-icons">dashboard</i> Gerenciar
</a>
@endcan

@can(Capabilities::MANAGE_PROJECT_USERS, $project)
<a href="{{route('admin.project.users',['id' => $project->id])}}" class="btn btn-warning btn-raised" data-toggle="tooltip" title="Gerenciar Usuários">
	<i class="material-icons">person</i> Usuários
</a>
@endcan

@can(Capabilities::MAKE_POST_PROJECT, $project)
<a href="{{route("admin.project.posts",['projectId' => $project->id])}}" class="btn btn-success btn-raised" data-toggle="tooltip" title="Posts do Projeto">
	<i class="material-icons">description</i> Posts 
</a>
@endcan
@can(Capabilities::UPDATE_PROJECT, $project)
<a href="{{route('admin.project.edit',['id' => $project->id])}}" class="btn btn-primary btn-raised" data-toggle="tooltip" title="Editar Projeto">
	<i class="material-icons">edit</i> Editar
</a>
@endcan