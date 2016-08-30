<div class="project-card box">
	<h4><a href="{{route('search',['category_id' => $project->category->id])}}">{{$project->category->name}}</a></h4>
	@if($project->images->count())
		<div class="project-image-cover">
			<a href="{{$project->url}}">
			<img class="img-responsive"
				 src="{{$project->imageCoverOrFirst()->getImageUrl()}}"
				 title="{{$project->imageCoverOrFirst()->title}}"/>
			</a>
		</div>
	@endif
	<h3><a href="{{$project->url}}">{{$project->title}}</a></h3>
	<p class="text-justify">{{StringUtil::limitaCaracteres($project->description,200,"...")}}</p>
	<div class="evaluation">
		@if($avgNote = ($project instanceof App\Models\DB\Project) ? $project->getAvgNote() : $project->avg_note)
			<span class="note">
				<input readonly class="project-note-value" type="number" value="{{$avgNote}}" min="{{config('starrating.min')}}" max="{{config('starrating.max')}}"/>
			</span>
		@endif
	</div>
	<div class="created-at text-right margin-top-10">
		<span class="label">Criado em:</span>
		<span class="date">{{$project->created_at->format('d/m/Y')}}</span>		
	</div>
	@if(isset($showControls) && $showControls)
	<div class="project-control margin-top-20 margin-bottom-10 text-right">
		@can(Capabilities::MANAGE_PROJECT, $project)
		<a href="{{route("admin.project.management.first",['id' => $project->id])}}" class="btn btn-default" data-toggle="tooltip" title="Gerenciar">
			<span class="glyphicon glyphicon-dashboard"></span>
		</a>
		@endcan
		@can(Capabilities::MAKE_POST_PROJECT, $project)
		<a href="{{route("admin.project.posts",['projectId' => $project->id])}}" class="btn btn-default" data-toggle="tooltip" title="Blog">
			<span class="glyphicon glyphicon-pencil"></span>
		</a>
		@endcan
		@can(Capabilities::UPDATE_PROJECT, $project)
		<a href="{{route('admin.project.edit',['id' => $project->id])}}" class="btn btn-default" data-toggle="tooltip" title="Editar">
			<span class="glyphicon glyphicon-edit"></span>
		</a>
		@endcan
		@can(Capabilities::DELETE_PROJECT, $project)
		<a href="{{route('admin.project.delete',['id' => $project->id])}}" class="btn btn-default delete-project" data-toggle="tooltip" title="Excluir">
			<span class="glyphicon glyphicon-trash"></span>
		</a>
		@endcan
	</div>
	@endif
</div>