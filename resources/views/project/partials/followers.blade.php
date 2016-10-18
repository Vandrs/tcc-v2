@if($followers->count())
<ul class="simple-list project-followers">
    @foreach($followers as $follower)
        <li><a href="{{route('user.view',['id' => $follower->id])}}">{{$follower->name}}</a></li>
    @endforeach
</ul>
@else
Seja o primeiro a seguir
@endif