@extends('layouts.app')

@section('title')
	{{ ucfirst($event->name) }}
@stop

@section('content')
	<h1><span>@if($event->private == 1) <span class="{{ glyph('lock') }}" title="Cet événement est privé"></span> @endif{{ ucfirst($event->name) }}</span> @if(isOwner($event)) <a title="Supprimer l'évenement" id="delete-event" }}" class="{{ glyph('trash') }} cursor-pointer"> </a> @endif</h1> 
	<hr />

	<p>Date : {{ $event->date->format('d/m/Y') }}</p>
	<p>Horaires : de {{ printDate($event->start, 'H:i:s', 'H:i') }} à {{ printDate($event->end, 'H:i:s', 'H:i') }}</p>
	<p>Lieu : @if($event->city != '') @if($event->adress != '') {{ $event->adress }}, à @endif {{ ucfirst($event->city) }} @else - @endif</p>
	<p>Description : </p>
	{!! $event->description !!}

	<br />
	<div class="playlists" id="playlists">
		
		<h1><span class="col-lg-3">Playlists</span> 

		@if($event->playlists->count() > 0)
			<nav class="playlist-nav col-lg-9">
				<?php $n=1; ?>
				@foreach($event->playlists as $p)
					<li title="Afficher la playlist" class="hvr-underline-from-center menu-playlist {{ $n == 1 ? 'active' : '' }}" id="menu-playlist-{{ $n++ }}" target="{{ $p->id }}">
						{{ ucfirst($p->name) }}
					</li>
				@endforeach
			</nav>

		@else
			<p class="no-playlist" align="center">Aucune playlist n'a été créée pour le moment.</p>
		@endif

		@if(isOwner($event))
			@if($event->playlists->count() < 3)
				<button title="Ajouter une playlist" class="hvr-underline-from-center add-playlist" id="add-playlist">+</button>
			@endif
		@endif

		</h1>
		<hr />

		<div class="event-playlist row" id="event-playlist">
			<div>
				@if($event->playlists->count() == 0)
					<p align="center">-</p>
				@endif
			</div>
		</div>
		@if($event->playlists->count() > 0)
			<span class="note">Note : Sans bloqueur de publicités vous risquez d'avoir des publicités au début des morceaux.</span>
		@endif
	</div>
	

	{{-- Modal delete event --}}
	<div class="modal fade" id="modal-delete" role="dialog">
		<div class="modal-dialog">

	  	<!-- Modal content-->
	      	<div class="modal-content">
	       	 	<div class="modal-header">
	          		<button type="button" title="Fermer la boite de dialogue" class="close" data-dismiss="modal">&times;</button>
	          		<h4 id="modal-title" class="modal-title">Supprimer l'événement</h4>
	        	</div>

		        <form id="delete-form" class="modal-form" method="post" action="{{ url('events/delete') }}">
		        	{!! csrf_field() !!}
			        <div class="modal-body">
				        <div class="row">
			        		<p class="text-danger"><b>Attention ! Cette action est irréversible !</b></p>
					         	<input hidden value="{{ $event->id }}" name="id" id="id" />
					        </div>
				        </div>
			        <div class="modal-footer">
			          	<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
			          	<button type="submit" class="btn btn-primary">Supprimer</button>
			        </div>
				</form>

	   		</div>
		</div>
	</div>

	<div class="modal fade" id="modal-add-playlist" role="dialog">
		<div class="modal-dialog">

	  	<!-- Modal content-->
	      	<div class="modal-content">
	       	 	<div class="modal-header">
	          		<button type="button" title="Fermer la boite de dialogue" class="close" data-dismiss="modal">&times;</button>
	          		<h4 id="modal-title" class="modal-title">Ajouter une playlist</h4>
	        	</div>

		        <form id="add-form" class="modal-form" method="post" action="{{ url('playlists/addplaylist') }}">
		        	{!! csrf_field() !!}
			        <div class="modal-body">
				        <div class="row">
			        		<p class="text-info col-lg-10 col-lg-offset-1"><b>Choisissez un nom pour votre playlist et sélectionnez le(s) style(s) de musique souhaité(s).</b></p>
					         	<input hidden value="{{ $event->id }}" name="event_id" id="event_id" />
					        <div class="form-group col-lg-10 col-lg-offset-1">
					        	<input class="form-control" type="text" name="name" placeholder="Nom de la playlist (facultatif)" />
					        </div>
					        <div class="form-group col-lg-10 col-lg-offset-1">
					        	<select style="height:200px" class="form-control" required multiple name="styles[]">
					        		<option selected disabled>Sélectionnez un ou plusieurs style(s) de musique...</option>
					        		<option id="select-all" value="0"> Tous les styles </option>
					        		@forelse(App\Style::orderBy('name', 'asc')->get() as $s)
					        			<option value="{{ $s->id }}">{{ ucfirst($s->name)}}</option>
					        		@empty 
					        		@endforelse
					        	</select>
					        	<br />
					        	<span style="display:block; margin-top:-10px" class="info"><i>Maintenez CTRL ou CMD (Mac) enfoncé pour sélectionner plusieurs styles.</i></span>
					        </div>
				        </div>
			        </div>
			        <div class="modal-footer">
			          	<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
			          	<button type="submit" class="btn btn-primary">Créer la playlist</button>
			        </div>
				</form>

	   		</div>
		</div>
	</div>

	<div class="modal fade" id="modal-delete-playlist" role="dialog">
		<div class="modal-dialog">

	  	<!-- Modal content-->
	      	<div class="modal-content">
	       	 	<div class="modal-header">
	          		<button type="button" title="Fermer la boite de dialogue" class="close" data-dismiss="modal">&times;</button>
	          		<h4 id="modal-title" class="modal-title">Supprimer une playlist</h4>
	        	</div>

		        <form id="delete-playlist-form" class="modal-form" method="post" action="{{ url('playlists/delete') }}">
		        	{!! csrf_field() !!}
		        	<input name="playlist_id" id="playlist_id" hidden />
			        <div class="modal-body">
				        <p>Événement : {{ ucfirst($event->name) }}</p>
				        <p id="name">Playlist : <span></span></p>
				        <p id="date">Créée le <span></span></p>
				        <p id="styles">Style(s) de musique : <span></span></p>
				        <p id="nb-musics">Musiques : <span></span></p>
				        <br />
				        <p class="text-danger"><b>Attention ! En supprimant la playlist vous perdrez les musiques associées.</b></p>
				        <p class="text-danger"><b>Cette action est irréversible !</b></p>
				    </div>
			        <div class="modal-footer">
			          	<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
			          	<button type="submit" class="btn btn-primary">Supprimer la playlist</button>
			        </div>
				</form>

	   		</div>
		</div>
	</div>

	<div id="iframe" class="iframe-div big">
		<span class="only-little {{ glyph('volume-up') }}"></span>
		<p class="title"></p>
		<button title="Arrêter la video" id="iframe-close" class="iframe-close">x</button>
		<iframe src="" id="player" class="player" allowfullscreen="true"></iframe>
		<button title="Réduire le cadre" class="{{ glyph('menu-down') }} toggle-view"></button>
	</div>
@stop

