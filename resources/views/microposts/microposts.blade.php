<ul class="media-list">
@foreach ($microposts as $micropost)
    <?php $user = $micropost->user; ?>
    <li class="media">
        <div class="media-left">
            <img class="media-object img-rounded" src="{{ Gravatar::src($user->email, 50) }}" alt="">
        </div>
        <div class="media-body">
            <div>
                {!! link_to_route('users.show', $user->name, ['id' => $user->id]) !!} <span class="text-muted">posted at {{ $micropost->created_at }}</span>
            </div>
            <div>
                <p>{!! nl2br(e($micropost->content)) !!}</p>
            </div>
             
             <div class="row">
             <div class = "col-md-1" style="margin-right:1%;">@include('micropost_favorite.favorite_button', ['micropost' => $micropost])</div>
             <div class = "col-md-1">@include('microposts.micropost_delete_button', ['micropost' => $micropost])</div>
            </div>
        </div>
    </li>
@endforeach
</ul>
{!! $microposts->render() !!}