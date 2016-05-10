@if(count($errors))
<div class="row margin-top-10 magin-bottom-10">
	<div class="col-xs-12">
		<div class="alert alert-danger alert-dismissible fade in" role="alert">
			<button type="button" data-dismiss="alert" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<ul class="alert-messages">
			@foreach($errors->all() as $error)
				<li>{{$error}}</li>
			@endforeach	
			</ul>
		</div>
	</div>
</div>
@elseif (session('msg'))
<div class="row">
	<div class="col-xs-12 margin-top-10">
		<div class="alert {{session('class_msg')}} alert-dismissible fade in" role="alert">
    		<button type="button" data-dismiss="alert" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	{!!session('msg')!!}
    	</div>
	</div>
</div>
@endif
