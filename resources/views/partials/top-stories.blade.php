@foreach($posts as $post)
	<div class="articles">
		<article class="item item--big {{ strtolower($post->category->name) }}">
			<a href="{{url($post->slug)}}" class="image">
				<figure>
					<img src="{{$post->image}}" alt="">
				</figure>
			</a>
			<div class="info">
				<a href="{{url($post->slug)}}">
					<h1>{{$post->title}}</h1>
				</a>
				<p>{{$post->description}}</p>
				<div class="meta-holder">
					<div class="meta">
						<figure class="avatar">
							<img src="{{$post->author->image}}" alt="">
						</figure>
						<div>by <a href="{{url($post->slug)}}" class="author">{{$post->author->name}}</a> on
							<span class="date">{{ (new DateTime($post->created_at))->format('m M, Y') }}</span></div>
					</div>
					<a href="{{url($post->slug)}}" class="btn">Read more</a>
				</div>
			</div>
			<a href="{{ url('category/' . strtolower($post->category->name)) }}" class="category">{{ $post->category->name }}</a>
		</article>
	</div>
	@endforeach


