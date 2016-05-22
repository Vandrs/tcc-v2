<div class="project-card box">
	<h4><a href="{{route('search',['category_id' => $project->category->id])}}">{{$project->category->name}}</a></h4>
	@if($project->images->count())
		<div class="project-image-cover">
			<img class="img-responsive"
				 src="{{$project->imageCoverOrFirst()->getImageUrl()}}"
				 title="{{$project->imageCoverOrFirst()->title}}"/>
		</div>
	@endif

	<h3><a href="{{route('site.project.view',['id' => $project->id])}}">{{$project->title}}</a></h3>
	<p class="text-justify">{{StringUtil::limitaCaracteres($project->description,200,"...")}}</p>
	<div class="evaluation">
		<span class="label">Nota:</span>
		<span class="note">{{($project instanceof App\Models\DB\Project) ? $project->getAvgNote() : $project->avg_note}}</span>		
	</div>
	<div class="created-at text-right margin-top-10">
		<span class="label">Criado em:</span>
		<span class="date">{{$project->created_at->format('d/m/Y')}}</span>		
	</div>
	@if(isset($showControls) && $showControls)
	<div class="project-control margin-top-20 margin-bottom-10 text-right">
		@can(Capabilities::UPDATE_PROJECT, $project)
		<a href="{{route('admin.project.edit',['id' => $project->id])}}" class="btn btn-default" data-toggle="tooltip" title="Editar">
			<span class="glyphicon glyphicon-edit"></span>
		</a>
		@endcan
		@can(Capabilities::DELETE_PROJECT, $project)
		<a href="#" class="btn btn-default" data-toggle="tooltip" title="Excluir">
			<span class="glyphicon glyphicon-trash"></span>
		</a>
		@endcan
	</div>
	@endif
</div>