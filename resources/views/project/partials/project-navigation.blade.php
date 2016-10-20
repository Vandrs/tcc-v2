
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
<a href="{{route("admin.project.posts",['projectId' => $project->id])}}" class="btn btn-danger btn-raised" data-toggle="tooltip" title="Posts do Projeto">
	<i class="material-icons">description</i> Posts 
</a>
@endcan
@can(Capabilities::UPDATE_PROJECT, $project)
<a href="{{route('admin.project.edit',['id' => $project->id])}}" class="btn btn-primary btn-raised" data-toggle="tooltip" title="Validações">
	<i class="material-icons">edit</i> Editar
</a>
@endcan
@can(Capabilities::MAKE_PROJECT_VALIDATION, $project)
<a href="{{route('admin.project.validations',['id' => $project->id])}}" class="btn btn-success btn-raised" data-toggle="tooltip" title="Validação do Projeto">
	<i class="material-icons">check</i> Validação
</a>
@endcan