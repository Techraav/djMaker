@extends('layouts.app')

@section('title')
	Événements
@stop

@section('content')
	<h1>Liste des futurs événements @if(Auth::check()) <a title="Créer un événement" href="{{ url('events/create') }}" class="{{ glyph('plus') }}"> </a> @endif</h1> 
	{{-- <a title="Créer un événement" href="{{ url('events/create') }}" class="{{ glyph('plus') }} create-event"> </a>  --}}
	<hr />
	<ul class="event-list">
		@forelse($events as $e)
			<li>
				<a class="event-title" href="{{ url('events/show/'.$e->slug) }}"><h2>{{ ucfirst($e->name) }}</h2></a>
				<!-- <hr /> -->
				<div class="infos">
					<p class="event-date"><b>Date</b> : <i>{{ $e->date->format('d F Y') }}</i></p>
					<p class="event-city"> <b>Ville</b> : <i>{{ $e->city != '' ? ucfirst($e->city) : '-' }}</i></p>
					<p class="styles"><b>Playlists</b> : <i>{{ $e->playlists->count() }}</i>
					@if($e->playlists->count() > 0)
						<ul class="playlist-styles">
						@foreach($e->playlists as $p)
							<li>
								@if ($p->styles->count() > 0)
									@for ($i = 0; $i < $p->styles->count()-1; $i++)
										<i>{{ ucfirst($p->styles[$i]->name) }},</i>
									@endfor

									@if($p->styles->count() > 1)
										<i>{{ ucfirst($p->styles[$p->styles->count()-1]->name) }}</i>
									@endif
								@else
									<i>{{ ucfirst($p->styles[$p->styles->count()-1]->name) }}</i>
								@endif
							</li>
						@endforeach
						</ul>
					@endif
					</p>
				</div>
			</li>
		@empty
			<p class="empty">Aucun événement n'est prévu pour le moment.</p>
		@endforelse
	</ul>
	<br />
	<p align="center">Vous souhaitez retrouver un événement passé ? <a href="{{ url('events/past') }}"><b>Cliquez ici</b></a> ! </p>


	<div class="row" align="right">
		{!!$events->render()!!}
	</div>
@stop