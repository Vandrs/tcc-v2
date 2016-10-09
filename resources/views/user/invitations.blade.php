@extends('layouts.admin')
@section('breadcrumbs')
	{!!Breadcrumbs::render('project.invitations')!!}
@endsection
@section('content')
	<div class="row margin-top-10">
		<div class="col-xs-12 ">
			@include('partials.view-errors')
			<div class="row">
            <div class="col-xs-12">
                <div class="invitationFeedbackArea">
                </div>
            </div>
        </div>
			<div class="row margin-top-10">
				<div class="col-xs-12">
					<table id="invitationsTable" class="table table-bordered table-striped" data-list-route="{{route('project.invitations.list')}}">
						<thead>
							<tr>
								<th>Data</th>	
								<th>Mensagem</th>
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection