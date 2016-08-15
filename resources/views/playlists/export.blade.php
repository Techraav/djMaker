@extends('layouts.app')

@section('title')
	Exporter vers YouTube
@stop

@section('content')
	<h1>Exporter une playlist vers YouTube</h1>
	<hr />

	<div class="event-playlist row" id="event-playlist">
		<div class="col-lg-offset-1 col-lg-10">
			<div class="add-music row">
				<h2 align="center">{{ ucfirst($playlist->name) }}</h2>
				<p class="playlist-infos" align="center"><span id="playlist-styles">Rock, Hard Rock, Métal</span> </p>
				<p class="playlist-infos" align="center">Musiques : <span id="nb-of-musics">29</span> </p>
				<br />
				<p align="center"><button id="export" class="btn btn-primary"> &nbsp; Exporter ! &nbsp; </button></p>
			</div>

			<table class="table table-hover" id="playlist">
				<thead>
					<tr>
						<td> Artiste - Titre </td>
						<td align="center" width="50px"> Contrôles </td>
						<td align="center" width="150px"> Likes/Dislikes </td>
						<td align="center" width="150px"> Validation </td>
					</tr>
				</thead>
				<tbody>	
					@forelse($videos as $v)
					<?php $mind = likesAndDislikes($v->likes); ?>
						<tr id="{{ $v->id }}">
							<td><a target="_BLANK" data-video="{{ $v->video->url }}" href="https://youtu.be/{{ $v->video->url }}">{{ $v->video->artist . ' - ' . $v->video->name }}</a></td>
							<td align="center"> <button class="glyphicon glyphicon-play play"></button> </td>
							<td align="center"> 
								<?php 
									$liked = false;
									$disliked = false;

									foreach($v->likes as $u)
									{
										dd($u);
									}
								?>
								<span class="glyphicon glyphicon-thumbs-up like"><span class="likes">{{$mind['likes']}}</span></span>
								<span class="glyphicon glyphicon-thumbs-down dislike "><span class="dislikes">{{$mind['dislikes']}}</span></span> 
							</td>
							<td align="center">
								<span class="glyphicon glyphicon-ok-circle validate colored"></span>
								<span class="glyphicon glyphicon-remove-circle refuse "></span> 
							</td>
						</tr>
					@empty
					<tr>
						<td align="center">-</td><td align="center">-</td><td align="center">-</td><td align="center">-</td>
					</tr>
					@endforelse
				</tbody>
			</table>

			<div class="paginator row" align="right">	
				<div align="col-lg-12 right">
					{!! $videos->render() !!}
				</div>
			</div>
		</div>
	</div>

	<div id="iframe">
		<p class="title"></p>
		<button title="Arrêter la video" id="iframe-close">x</button>
		<iframe src="" id="player" allowfullscreen="true"></iframe>
	</div>

	<div class="modal fade" id="modal-export" role="dialog">
		<div class="modal-dialog">

	  	<!-- Modal content-->
	      	<div class="modal-content">
	       	 	<div class="modal-header">
	          		<button type="button" title="Fermer la boite de dialogue" class="close" data-dismiss="modal">&times;</button>
	          		<h4 id="modal-title" class="modal-title">Exporter la playlist</h4>
	        	</div>

		        <form id="export-form" class="modal-form" method="post" action="{{ url('playlists/export') }}">
		        	{!! csrf_field() !!}
			        <div class="modal-body">
				        <div class="row">
					        <div class="col-lg-10 col-lg-offset-1">
				        		<p align="center" class="text-info"><b>Veuillez remplir le formulaire suivant pour exporter votre playlist vers YouTube</b></p>
						         	<input hidden value="{{ $playlist->id }}" name="playlist_id" id="playlist_id" />
						         	<div class="form-group">
						         		<label class="label-control">Titre de la playlist : </label>
						         		<input type="text" name="title" class="form-control" placeholder="Titre de la playlist" value="{{ $playlist->name }}">
						         	</div>
						         	<div class="form-group">
						         		<label class="label-control">Description :</label>
						         		<textarea rows=8 name="description" class="form-control"></textarea>
						         	</div>
						         	<div class="form-group">
						         		<label class="label-control">
						         			<input type="checkbox" name="privacy">
						         			Playlist privée 
						         		</label>
						         	</div>
						        </div>
					        </div>
				        </div>
			        <div class="modal-footer">
			          	<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
			          	<button type="submit" class="btn btn-primary">Valider et exporter la playlist !</button>
			        </div>
				</form>

	   		</div>
		</div>
	</div>
@stop

@section('js')
	<script type="text/javascript">
	$('#export').click(function(){
		$('#modal-export').modal('show');
	});

	// PLAYER
    $('button.play').click(	function()
    {
	    if(!$(this).hasClass('playing'))
	    {	
	    	$('.glyphicon-stop').removeClass('glyphicon-stop').addClass('glyphicon-play');
	    	var el = $(this);
	    	var tr = el.parents('tr');
	    	var id = tr.attr('id');
	    	var iframe = $('iframe#player');
			var name = $('tr#'+id+'>td:first-child>a').text();

	    	var yt_link = $('tr#'+id+'>td:first-child>a').attr('data-video');

	    	console.log(yt_link);
	    	iframe.attr('src', 'https://www.youtube.com/embed/'+yt_link+'?autoplay=1');
	    	$('#iframe').css('display', 'block');
	    	$('#iframe #title').remove();
			$('#iframe').prepend('<p id="title" class="title"> '+name+'</p>');
	    	$('#iframe').animate({'opacity': '1'}, 1000);

	    	$(this).addClass('playing');	
	    	$(this).addClass('glyphicon-stop');
	    	$(this).removeClass('glyphicon-play');
	    }else
	    {	
	    	$('#iframe').animate({'opacity': '0'}, 700, function(){
	    		$('#player').attr('src', '');
				$('#iframe').css('display', 'none');
	    	})
	    	$(this).removeClass('glyphicon-stop');
	    	$(this).addClass('glyphicon-play');
	    	$(this).removeClass('playing');
	    }
    });
	</script>
@stop