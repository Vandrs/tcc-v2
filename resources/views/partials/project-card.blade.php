<div class="project-card box">
	<h4>{{$project->category->name}}</h4>
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
</div>