<div class="row">
	@if($users->count())
	<div class="col-xs-12">
		<h2 class="form-section-title">Usuários encontrados</h2>
	</div>
	<div class="col-xs-12">
		<div class="row">
		@foreach($users as $key => $user)
			<div class="col-xs-12 col-sm-4 col-md-3 margin-top-10">
				<div class="user-card box" data-user-id="{{$user->id}}">
					<h4><a href="{{route('user.view',['id' => $user->id])}}">{{$user->name}}</a></h4>
					<div class="user-card-info">
						@if($work = $user->getCurrentOrLastWork())
						<div class="work-info margin-top-10">
							<b>{{$work->title}}</b> na empresa {{$work->company}}
						</div>
						<div class="work-date text-left">
							De {{$work->started_at->format('m/Y')}}  até @if($work->ended_at) {{$work->ended_at->format("m/Y")}} @else atualmente. @endif
						</div>
						@endif
						@if($graduation = $user->getCurrentOrLastGraduation())
						<div class="graduation-info margin-top-10">
							{{$graduation->course}} ({{$graduation->institution}})
						</div>
						<div class="graduation-date text-left">
							Conclusão: {{$graduation->conclusion_at->format('m/Y')}}
						</div>
						@endif
					</div>
					<div class="user-card-controls text-right">
						<button class="btn btn-fab btn-fab-mini view-modal-profile" title="Ver Perfil" data-toggle="tooltip" data-user-id="{{$user->id}}">
							<i class="material-icons">person</i>
						</button>
						<button class="btn btn-fab btn-fab-mini show-invite-modal" title="Convidar para o Projeto" data-user-id="{{$user->id}}" data-toggle="tooltip">
							<i class="material-icons">check</i>
						</button>
					</div>
				</div>
			</div>
		@endforeach
		</div>
	</div>
	@else
	<div class="col-xs-12">
		<h2 class="form-section-title">Nenhum usuário encontrados com os filtros informados.</h2>
	</div>
	@endif
</div>