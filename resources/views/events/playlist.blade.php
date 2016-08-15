@forelse($videos as $v)
	<p>{{ $v->artist }} - {{ $v->name }}</p>
@empty
-
@endforelse

{!! $videos->render() !!}

<div class="row" align="right">
	<ul class="pagination">
		<li class="disabled"><span>«</span></li>
		<li class="active"><span>1</span></li>
		<li><a href="http://djmaker.fr/events?page=2">2</a></li>
		<li><a href="http://djmaker.fr/events?page=3">3</a></li> 
		<li><a href="http://djmaker.fr/events?page=2" rel="next">»</a></li>
	</ul>
</div>