<div class="row user-profile-area">
	<div class="col-xs-12 personal-info-area margin-top-10">
		<div class="row">
			@if($user->photo)
			<div class="col-xs-12 col-sm-6 col-md-8">
			@else
			<div class="col-xs-12">
			@endif
				<div class="row">
					<div class="col-xs-12">
						<h1 class="h2">{{$user->name}}</h1>
					</div>
				</div>
				<div class="row">
					@if(count($user->skills))
					<div class="col-xs-12 skilss-area margin-top-20">
						<h4 class="form-section-title">
							Habilidades
						</h4>
						<div class="skills-area-items">
						@foreach($user->skills as $skill)
							<span class="label label-primary">
								{{$skill}}
							</span>
						@endforeach
						</div>
					</div>
					@endif
				</div>
			</div>
			@if($user->photo)
			<div class="col-xs-12 col-sm-6 col-md-4">
				<img src="{{$user->photo}}" id="photoProfileModal">
			</div>
			@endif
		</div>
	</div>

	@if($user->works->count())
	<div class="col-xs-12 work-info-area margin-top-20">
		<h4 class="form-section-title">Experiência Proficional</h4>
		@foreach($user->works->sortBy('order') as $work)
		<div class="user-work-container">
			<div class="work-title">
				<h2>{{$work->title}}</h2>
			</div>
			<div class="work-company">
				({{$work->company}})
			</div>
			<div class="work-description">
				{{$work->description}}
			</div>
			<div class="work-period text-right">
				<span class="label">De {{$work->started_at->format('m/Y')}}  até @if($work->ended_at) {{$work->ended_at->format("m/Y")}} @else atualmente. @endif</span>
			</div>
		</div>
		@endforeach
	</div>
	@endif

	@if($user->graduations->count())
	<div class="col-xs-12 graduation-info-area margin-top-20">
		<h4 class="form-section-title">Formação</h4>
		@foreach($user->graduations->sortBy('order') as $graduation)
		<div class="user-graduation-container">
			<div class="graduation-title">
				<h2>{{$graduation->course}}</h2>
			</div>
			<div class="graduation-institution">
				{{$graduation->institution}} <span class="label label-success">Conclusão: {{$graduation->conclusion_at->format('m/Y')}}</span>
			</div>
		</div>
		@endforeach
	</div>
	@endif

</div>