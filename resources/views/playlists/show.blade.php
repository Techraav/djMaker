@extends('layouts.app')

@section('title')
	Playlist : {{ ucfirst($playlist->name) }}
@stop

@section('content')
	<h1>{{ ucfirst($playlist->name) }}</h1>
	<hr />

	<div class="event-playlist row" id="event-playlist">
		<div class="col-lg-offset-1 col-lg-10">
			<div align="center" class="add-music row">
				<h2 align="center">{{ ucfirst($playlist->name) }}</h2>
				<p class="playlist-infos" align="center">
					<span id="playlist-styles">

					</span> 
				</p>
				<p class="playlist-infos" align="center">Musiques : <span id="nb-of-musics" data-per-page="{{ $videos->perPage() }}">{{ $videos->total() }}</span> </p>
				<br />
				<button title="Écouter un extrait de 30s de chaque musique" id="demo" class="btn btn-primary"><span class="{{ glyph('play') }}"></span>Lancer la démo</button>
			</div>

			<table data-playlist-id="{{ $playlist->id }}" class="table table-hover" id="playlist">
				<thead>
					<tr>
						<td title="Trier selon les artistes" class="can-click"> 
							<a href="{{ url($urlArtist) }}" class="filter {{ $top == 'name' ? 'top' : 'bottom' }}" id="#filter-artist">
								@if($top == 'name')
									<span class="{{ glyph('triangle-top') }}"></span>
								@else
									<span class="{{ glyph('triangle-bottom') }}"></span>
								@endif
								Artiste - Titre 
							</a> 
						</td>
						<td align="center" width="50px"> 
							Contrôles 
						</td>
						<td title="Trier selon la popularité" class="can-click" align="center" width="150px"> 
							<a href="{{ url($urlScore )}}" class="filter" id="#filter-score">
								@if($top == 'score')
									<span class="{{ glyph('triangle-top') }}"></span>
								@else
									<span class="{{ glyph('triangle-bottom') }}"></span>
								@endif
								Likes/Dislikes 
							</a> 
						</td>
						<td title="Trier selon la validation" class="can-click" align="center" width="150px"> 
							<a href="{{ url($urlValidation) }}" class="filter" id="#filter-validation">
								@if($top == 'validation')
									<span class="{{ glyph('triangle-top') }}"></span>
								@else
									<span class="{{ glyph('triangle-bottom') }}"></span>
								@endif
								Validation 
							</a> 
						</td>
					</tr>
				</thead>
				<tbody>	
					@forelse($videos as $v)
					<?php 
						$mind = likesAndDislikes($v->likes); 
						$liked = false;
						$disliked = false;

						if(Auth::check())
						{
							foreach($v->likes as $l)
							{
								if($l->user_id == Auth::user()->id)
								{
									$liked = $l->value == 1;
									$disliked = !$liked;
								}
							}
						}
					?>
						<tr id="{{ $v->id }}">
							<td>
								<a target="_BLANK" data-video="{{ $v->video->url }}" href="https://youtu.be/{{ $v->video->url }}" data-song="{{ $v->video->name }}" data-artist="{{ $v->video->artist }}">
									{{ $v->video->artist }} - {{ $v->video->name }}
								</a>
							</td>
							<td align="center"> <button class="glyphicon glyphicon-play play"></button> </td>
							<td align="center"> 
								<span class="glyphicon glyphicon-thumbs-up like {{ $liked ? 'colored' : '' }}"><span class="likes">{{$mind['likes']}}</span></span>
								<span class="glyphicon glyphicon-thumbs-down dislike {{ $disliked ? 'colored' : '' }} "><span class="dislikes">{{$mind['dislikes']}}</span></span> 
							</td>
							<td align="center">
								<span class="glyphicon glyphicon-ok validate {{ $v->validation == 1 ? 'colored' : '' }}"></span>
								<span class="glyphicon glyphicon-remove refuse {{ $v->validation == -1 ? 'colored' : '' }}"></span> 
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

			<input type="search" id="searchForMusic" class="form-control" name="search" placeholder="Rechercher une musique dans cette playlist" />

		</div>
	</div>

	<div class="iframe-div big" id="iframe">
		<span class="only-little {{ glyph('volume-up') }}"></span>
		<p class="title"></p>
		<button title="Arrêter la video" class="iframe-close">x</button>
		<iframe class="player" src="" id="player" allowfullscreen="true"></iframe>
		<button title="Réduire le cadre" class="{{ glyph('menu-down') }} toggle-view"></button>
	</div>

	<div class="iframe-div big" id="iframe-demo">
		<div class="row">
			<div class="title-div col-lg-4">
				<div>
					<p class="title previous artist"></p>
					<p class="title previous song"></p>
				</div>
			</div>
			<div class="title-div col-lg-4" id="current">
				<button title="Revenir au morceau précédent" id="previous" class="{{ glyph('step-backward') }}"></button>
				<div>
					<p class="title current artist"></p>
					<p class="title current song"></p>
				</div>
				<button title="Passer au morceau suivant" id="next" class="{{ glyph('step-forward') }}"></button>
			</div>
			<div class="title-div col-lg-4">
				<div>
					<p class="title next artist"></p>			
					<p class="title next song"></p>			
				</div>
			</div>
		</div>
		<div class="row">
			<iframe class="player" src="" id="player-demo" allowfullscreen="true"></iframe>
		</div>
		<button title="Arrêter la video" class="iframe-close">x</button>
		<button title="Réduire le cadre" class="{{ glyph('menu-down') }} toggle-view"></button>
		<div id="progress-bar" class="progress-bar"></div>
		<div class="video-number"><span class="number"></span>/<span class="total"></span></div>
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
	    	stop($('#iframe-demo'));
	    	var el = $(this);
	    	var tr = el.parents('tr');
	    	var id = tr.attr('id');
	    	var iframe = $('iframe#player');
			var name = $('tr#'+id+'>td:first-child>a').text();

	    	var yt_link = $('tr#'+id+'>td:first-child>a').attr('data-video');

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
	    	stop($('#iframe'));
	    }
    });
	</script>

	<script type="text/javascript">
		
		var TIMER = 15000;
		var littleDemo = false;
		var interval;

		function updateIframeDemo(n, videos) {
			div = $('#iframe-demo');
			iframe = $('#player-demo');
			previousArtist = $('#iframe-demo p.previous.artist');
			previousSong = $('#iframe-demo p.previous.song');
			currentArtist = $('#iframe-demo p.current.artist');
			currentSong = $('#iframe-demo p.current.song');
			nextArtist = $('#iframe-demo p.next.artist');
			nextSong = $('#iframe-demo p.next.song');
	    	iframe.attr('src', 'https://www.youtube.com/embed/'+videos[n]['link']+'?autoplay=1&start=60');
			currentArtist.html(videos[n]['artist']);
			currentSong.html(videos[n]['song']);
			$('.video-number .number').html(n+1);

			$('#progress-bar').delay(250).animate({'width': '100%'}, TIMER-500, 'linear', function()
			{
				if(videos[n+1] != false)
				{
					// $(this).css('width', '0');
					$(this).animate({'width': '0'}, 250);
				}	
			});


			if(n == 0)
			{
				previousArtist.html('-');
				previousSong.html(' ');
				nextArtist.html(videos[n+1]['artist']);
				nextSong.html(videos[n+1]['song']);
			}
			else if(videos[n] == false)
			{
				iframe.attr('src', '');
				currentArtist.html('-');
				currentSong.html(' ');
				previousArtist.html(videos[n-1]['artist']);
				previousSong.html(videos[n-1]['song']);
				nextArtist.html('&nbsp;');
				nextSong.html(' ');
			}
			else if(videos[n+1] == false)
			{
				previousArtist.html(videos[n-1]['artist']);
				previousSong.html(videos[n-1]['song']);
				nextArtist.html('-');
				nextSong.html(' ');
			}
			else
			{
				previousArtist.html(videos[n-1]['artist']);
				previousSong.html(videos[n-1]['song']);
				nextArtist.html(videos[n+1]['artist']);
				nextSong.html(videos[n+1]['song']);
			}
		}

		function toggleViewDemo(elementStr, littleDemo)
		{
			var el = $(elementStr);
			if(littleDemo)
			{
				el.addClass('little');
				el.removeClass('big');
				$('#iframe-demo .toggle-view.glyphicon-menu-down').addClass('glyphicon-menu-up');
				$('#iframe-demo .toggle-view.glyphicon-menu-down').removeClass('glyphicon-menu-down');
				$('#iframe-demo .toggle-view').attr('title', 'Agrandir le cadre');
			}else
			{
				el.removeClass('little');
				el.addClass('big');
				$('#iframe-demo .toggle-view.glyphicon-menu-up').addClass('glyphicon-menu-down');
				$('#iframe-demo .toggle-view.glyphicon-menu-up').removeClass('glyphicon-menu-up');
				$('#iframe-demo .toggle-view').attr('title', 'Réduire le cadre');
			}
		}	    

		function stop(el)
	    {
	    	el.animate({'opacity': '0'}, 700, function(){
				el.css('display', 'none');
	    	});
	    	clearInterval(interval);
		    $('.player').attr('src', '');
	    	$('.glyphicon-stop').removeClass('playing');
	    	$('.glyphicon-stop').removeClass('glyphicon-stop').addClass('glyphicon-play');
	    }

	    function reloadProgress()
	    {
	    	$('#progress-bar').remove();
			$('#iframe-demo').append('<div id="progress-bar" class="progress-bar"></div>');
	    }

		function startDemo(){

			clearInterval(interval);
			// INITIALISATION
			var perPage = $('#nb-of-musics').attr('data-per-page');
			var nbMusics = $('#nb-of-musics').text();
			var playlistId = $('table#playlist').attr('data-playlist-id');
			var tr = $('table#playlist tbody tr');
			var videos = [];
			var stop = false;
			$('.video-number .total').html(nbMusics);
			reloadProgress();

			$('#iframe-demo #previous').addClass('disabled');
			$('#iframe-demo #next').removeClass('disabled');

			if(nbMusics <= perPage)
			{
				// Sans requête

				for(var i=0; i<nbMusics; i++)
				{
					var id = tr[i].id;
					var a = $('tr#'+id+' td:first-child a')[0];
					var link = a.attributes['data-video'].nodeValue;
					var song = $('tr#'+id+' td:first-child a').data('song');
					var artist = $('tr#'+id+' td:first-child a').data('artist');
					videos[i] = {'link': link, 'song': song, 'artist': artist};
				}
				videos[nbMusics] = false;

				// IFRAME

	    		$('#iframe-demo').css('display', 'block');
	    		$('#iframe-demo').animate({'opacity': '1'}, 1000);

				var n = 0;

				updateIframeDemo(n, videos);

				interval = setInterval(function(){
					$('button#previous').removeClass('disabled');
					$('button#next').removeClass('disabled');
					n++;
					if(n >= nbMusics-1)
					{
						clearInterval(interval);
						$('button#next').addClass('disabled');
					}

					updateIframeDemo(n, videos);						
				}, TIMER);

				$('.iframe-close').click(function()
				{
					clearInterval(interval);
				});

				$('button#next').click(function(){
					if(!$(this).hasClass('disabled'))
					{
						var el = $(this);
						if(n != nbMusics)
						{
							reloadProgress();
							$('button#previous').removeClass('disabled');
							$('button#next').removeClass('disabled');
							clearInterval(interval);
							n++;
							updateIframeDemo(n, videos);
							if(n >= nbMusics-1)
							{
								el.addClass('disabled');
							}else{
								interval = setInterval(function(){
									$('button#previous').removeClass('disabled');
									$('button#next').removeClass('disabled');
									n++;
									if(n == nbMusics-1)
									{
										clearInterval(interval);
										$('button#next').addClass('disabled');
									}

									updateIframeDemo(n, videos);						
								}, TIMER);
							}


						}
					}
				});

				$('button#previous').click(function(){
					if(!$(this).hasClass('disabled'))
					{
						var el = $(this);
						if(n != 0)
						{
							reloadProgress();
							$('button#previous').removeClass('disabled');
							$('button#next').removeClass('disabled');
							clearInterval(interval);
							n--;
							updateIframeDemo(n, videos);
							if(n <= 0)
							{
								el.addClass('disabled');
							}

							interval = setInterval(function(){
								$('button#previous').removeClass('disabled');
								$('button#next').removeClass('disabled');
								n++;
								if(n == nbMusics-1)
								{
									clearInterval(interval);
									$('button#next').addClass('disabled');
								}	

								updateIframeDemo(n, videos);						
							}, TIMER);
						}
					}
				});

			}
			else // Avec requete Ajax
			{
				$.ajax({
					type: 'get',
					url: '/playlists/'+playlistId+'/videos',
					success: function(data){
						videos = data
						videos[data.length] = false;

						// IFRAME

			    		$('#iframe-demo').css('display', 'block');
			    		$('#iframe-demo').animate({'opacity': '1'}, 1000);

						var n = 0;

						updateIframeDemo(n, videos);

						var interval;
						interval = setInterval(function(){
							$('button#previous').removeClass('disabled');
							$('button#next').removeClass('disabled');
							n++;
							if(n >= nbMusics)
							{
								clearInterval(interval);
								$('button#next').addClass('disabled');
							}

							updateIframeDemo(n, videos);						
						}, TIMER);

						$('.iframe-close').click(function()
						{
							clearInterval(interval)
						});

						$('button#next').click(function(){
							if(!$(this).hasClass('disabled'))
							{
								var el = $(this);
								if(n != nbMusics)
								{
									reloadProgress();
									$('button#previous').removeClass('disabled');
									$('button#next').removeClass('disabled');
									clearInterval(interval);
									n++;
									updateIframeDemo(n, videos);
									if(n >= nbMusics-1)
									{
										el.addClass('disabled');
									}else{
										interval = setInterval(function(){
											$('button#previous').removeClass('disabled');
											$('button#next').removeClass('disabled');
											n++;
											if(n == nbMusics-1)
											{
												clearInterval(interval);
												$('button#next').addClass('disabled');
											}

											updateIframeDemo(n, videos);						
										}, TIMER);	
									}
								}
							}
						});

						$('button#previous').click(function(){
							if(!$(this).hasClass('disabled'))
							{
								var el = $(this);
								if(n != 0)
								{
									reloadProgress();
									$('button#previous').removeClass('disabled');
									$('button#next').removeClass('disabled');
									clearInterval(interval);
									n--;
									updateIframeDemo(n, videos);
									if(n <= 0)
									{
										el.addClass('disabled');
									}

									interval = setInterval(function(){
										$('button#previous').removeClass('disabled');
										$('button#next').removeClass('disabled');
										n++;
										if(n == nbMusics-1)
										{
											clearInterval(interval);
											$('button#next').addClass('disabled');
										}	

										updateIframeDemo(n, videos);						
									}, TIMER);
								}
							}
						});
					},
					error: function(){
						flashMessage('Une erreur est survenue, impossible de lancer la démonstration.');
					}
				})

			}
		}

		$('#iframe-demo .toggle-view').click(function(){
			var elementStr = $('#iframe-demo');
			littleDemo = !littleDemo;
			toggleViewDemo(elementStr, littleDemo);
		})

		$('button#demo').click(function(){
			stop($('#iframe'));
			startDemo();
		});
	</script>

	<script type="text/javascript">

		$('#searchForMusic').bind('input', function()
		{
			var str = $(this).val();

			$('#playlist-search-video').remove();

			if(str.length != 0)
			{
				var playlist_id = $('#playlist').data('playlist-id');

				$.ajax({
					url: '/playlists/searchmusic',
					type: 'post',
					data: {
						_token: $('meta[name=csrf-token]').attr('content'),
						search: str,
						playlist_id: playlist_id 
					},
				sucess: function(data){
					$('#playlist-search-video').remove();

					// printSearchTable(data)
					console.log(data);

				},
				error: function(data){
					$('#playlist-search-video').remove();
					console.log(data);
					flashMessage('Impossible d\'effectuer la recherche. Veuillez recharger la page puis réessayez.');
				}
				
				});
			}
		});

	// function printSearchTable(response, playlist_id)
	// {
	// 	var playlist = '<h3 align="center">Rechercher une musique</h3>'

	// 	playlist += '<table class="table table-hover" id="playlist">';
	// 	playlist +=		'<thead>';
	// 	playlist +=			'<tr>';
	// 	// playlist +=				'<td> Utilisateur </td>';
	// 	playlist +=				'<td> Artiste - Titre </td>';
	// 	playlist +=				'<td align="center" width="50px"> Contrôles </td>';
	// 	playlist +=				'<td align="center" width="150px"> Likes/Dislikes </td>';
	// 	playlist +=				'<td align="center" width="150px"> Validation </td>';
	// 	playlist +=			'</tr>';
	// 	playlist +=		'</thead>';
	// 	playlist +=		'<tbody>';

	// 	for(var i = 0; i<videos.length; i++)
	// 	{
	// 		var liked = false;
	// 		var disliked = false;
	// 		var validated = false;
	// 		var refused = false;
	// 		var user_id = response.user_id;

	// 		var likesOrDislikes = isLikedOrDisliked(videos[i], response);

	// 		if(user_id != 'null')
	// 		{
	// 			var mind = likesOrDislikes['value'];
	// 			if(mind != false)
	// 			{
	// 				liked = mind == 1;
	// 				disliked = mind == -1;
	// 			}
	// 		}

	// 		var validation = isValidatedOfRefused(videos[i]);
	// 		if(validation != false)
	// 		{
	// 			validated = validation == 1;
	// 			refused = validation == -1;
	// 		}

	// 		var likes = likesOrDislikes['1'];
	// 		var dislikes = likesOrDislikes['-1'];

	// 		var v = videos[i];
	// 		playlist +=		'<tr id="'+ v.id +'">';
	// 		// playlist +=			'<td> '+ /*v.user.first_name +' '+ v.user.last_name */ '' +' </td>';
	// 		playlist +=			'<td> <a target="_BLANK" data-video="'+v.video.url+'" href="https://youtu.be/'+ v.video.url +'">'+ v.video.artist + ' - ' + v.video.name +'</a>  </td>';
	// 		playlist +=			'<td align="center"> <button title="Play/Stop" class="glyphicon glyphicon-play play"></button> </td>';
	// 		playlist +=			'<td align="center"> ';
	// 		if(response.user_id > 0)
	// 		{
	// 			playlist +=			'<button class="glyphicon glyphicon-thumbs-up like '+(user_id == 'null' ? 'disabled' : liked ? 'colored' : '' )+'">';
	// 			playlist +=				'<span class="likes">'+likes+'</span>';
	// 			playlist += 		'</button>';
	// 			playlist +=			'<button class="glyphicon glyphicon-thumbs-down dislike '+(user_id == 'null' ? 'disabled' : disliked ? 'colored' : '' )+'">';
	// 			playlist +=				'<span class="dislikes">'+dislikes+'</span>';
	// 			playlist +=			'</button>';
	// 		}
	// 		else
	// 		{
	// 			playlist +=			'<span class="glyphicon glyphicon-thumbs-up like '+(user_id == 'null' ? 'disabled' : liked ? 'colored' : '' )+'">';
	// 			playlist +=				'<span class="likes">'+likes+'</span>';
	// 			playlist += 		'</span>';
	// 			playlist +=			'<span class="glyphicon glyphicon-thumbs-down dislike '+(user_id == 'null' ? 'disabled' : disliked ? 'colored' : '' )+'">';
	// 			playlist +=				'<span class="dislikes">'+dislikes+'</span>';
	// 			playlist +=			'</span>';
	// 		}
	// 		playlist +=			'</td>';
	// 		playlist +=			'<td align="center">';

	// 		if(user_id == response.playlists.event[0].user_id)
	// 		{
	// 			playlist += 		'<button class="glyphicon glyphicon-ok validate '+(validated ? 'colored' : '' )+'"></button>';
	// 			playlist += 		'<button class="glyphicon glyphicon-remove refuse '+(refused ? 'colored' : '' )+'"></button> ';
	// 		}
	// 		else
	// 		{
	// 			playlist += 		'<span class="glyphicon glyphicon-ok validate '+(validated ? 'colored' : '' )+'"></span>';
	// 			playlist += 		'<span class="glyphicon glyphicon-remove refuse '+(refused ? 'colored' : '' )+'"></span> ';
	// 		}

	// 		playlist += 		'</td>';
	// 		playlist +=		'</tr>';
	// 	}

	// 	playlist +=		'</tbody>';
	// 	playlist +=	'</table>'

	// 	$('#playlist-search-video>div').html(playlist);

	// 	if(data.last_page > 1)
	// 	{
	// 		$('#playlist-search-video>div').append('<div class="paginator row" align="right"></div>');
	// 		var pagination = '';
	// 		pagination += '<div align="col-lg-12 right">';
	// 		pagination += 	'<ul class="pagination">';
	// 		pagination += 		'<li'+ (1 == data['from'] ? ' class="disabled">' : '> <a href="/playlists/'+playlist_id+'?page='+(data['from']-1)+'">') +'<span> «'+ (0 != data['from'] ? '</a>' : '')+'</span></li>';					for(var i=1; i<=data.last_page; i++)
	// 		{
	// 			pagination += 		'<li'+ (i == data['from'] ? ' class="active">' : '> <a href="/playlists/'+playlist_id+'?page='+i+'">') +'<span>'+ i + (i != data['from'] ? '</a>' : '')+'</span></li>';
	// 		}
	// 		pagination += 		'<li'+ (data.last_page == data['from'] ? ' class="disabled">' : '> <a href="/playlists/'+playlist_id+'?page='+(data['from']+1)+'">') +'<span> »'+ (data.last_page != data['from'] ? '</a>' : '')+'</span></li>';
	// 		pagination +=	'</ul>';
	// 		pagination += '</div>';

	// 		$('#playlist-search-video .paginator').html(pagination);
	// 	}
	// }




	</script>
@stop