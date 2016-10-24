@if($followers->count())
<ul class="simple-list project-followers">
    @foreach($followers as $follower)
        <li><a href="#" class="viewModalProfile" data-id="{{$follower->id}}">{{$follower->name}}</a></li>
    @endforeach
</ul>
@else
Seja o primeiro a seguir
@endif