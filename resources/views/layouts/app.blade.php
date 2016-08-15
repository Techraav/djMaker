<?php
	$nd = Carbon\Carbon::today()->addMonth();
	$incomingEvents = App\Event::where('private', 0)->where('active', 1)->where('date', '>=', DB::raw('NOW()'))->where('date', '<=', $nd)->orderBy('date', 'ASC')->get();
	$lastNews = App\News::where('active', 1)->where('published_at',  '<=', DB::raw('NOW()'))->orderBy('published_at', 'DESC')->limit(5)->get();
?>

<!DOCTYPE html>
<html>
<head>
	<title>DjMaker | @section('title') Accueil @show </title>
	<link rel="stylesheet" type="text/css" href="{{ url('vendor/css/bootstrap.css') }}" />
	{{-- <link rel="stylesheet/less" type="text/css" href="{{ url('vendor/css/style.less') }}" /> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />
	<link rel="stylesheet" type="text/css" href="{{ url('vendor/css/less.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ url('vendor/css/css.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ url('vendor/css/hover.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ url('vendor/js/jquery-ui/jquery-ui.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ url('vendor/js/timepicker/stylesheets/wickedpicker.css') }}">
	<link href="{{ 	URL::asset('vendor/js/file-input/css/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css" />
	<script src="{{ URL::asset('vendor/js/less.js') }}"></script>
	<script src="{{ URL::asset('vendor/js/jquery.js') }}"></script>
	<script src="{{ URL::asset('vendor/js/bootstrap.min.js') }}"></script>
</head>
<body>

<header>
	<div class="banner" id="banner">
		<a href="{{ url('/') }}">
			<h1>DjMaker</h1>
		</a>
		<div class="slideshow">
			<ul class="bxslider">
				<li id="banner1" class="banner-component">
					<h3>Vous organisez un événement ? </h3>
					<img alt="" src="{{ url('vendor/media/img/banner2.png') }}">
				</li>
				<li id="banner2" class="banner-component">
					<h3>Créez vos playlists </h3>
					<img alt=""  src="{{ url('vendor/media/img/banner3.jpg') }}">
				</li>
				<li id="banner3" class="banner-component">
					<h3>Et passez la meilleure des soirées !</h3>
					<img alt=""  src="{{ url('vendor/media/img/banner4.jpg') }}">
				</li>
			</ul>
		</div>
	</div>

	@if(Auth::guest())
		<div class="user">
			<a href="{{ url('login') }}">Connexion</a>
			<a href="{{ url('register') }}">Inscription</a>
		</div>
	@endif	

	<div id="menu-replace" style="display:none; height:63px;"></div>
	<nav class="menu menu-relative" id="menu">
		<li><a href="{{ url('/') }}">Accueil</a></li>
		<li><a href="{{ url('events') }}">Événements</a></li>
		<li><a href="#">Menu3</a></li>
		<li><a href="#">Menu4</a></li>

		@if(Auth::check())
			<li class="dropdown menu-right navbar-right">
	          <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false">  <span class="glyphicon glyphicon-user">
	                
	          </span><span class="caret"></span></a>
	          <ul class="dropdown-menu user-menu" role="menu">
	            	<li><a href="{{ '' }}"> <span class="glyphicon glyphicon-log-out"></span> &nbsp; Déconnexion</a></li>
	            	<li><a href="{{ url('notifications') }}"> <span class="glyphicon glyphicon-log-out"></span> &nbsp; Déconnexion</a></li>
	            	<li><a href="{{ url('logout') }}"> <span class="glyphicon glyphicon-log-out"></span> &nbsp; Déconnexion</a></li>
	         	</ul>
	        </li>
		@endif
	</nav>
</header>

<!-- <div class="div-breadcrumb">
	<ul class="breadcrumb">
		<li><a href="{{ url('/') }}">Accueil</a></li>
		@yield('breadcrumb')
	</ul>
</div> -->

@include('flash::message')

<div class="content col-lg-9">
	<div id="content-jumbotron" class="jumbotron">	
		@yield('content')
	</div>
</div>

<div class="content aside col-lg-3">
	<div class="jumbotron events">
		<div class="next-prev">
			<div title="Voir les événements précédents" class="previous {{ glyph('menu-left') }} not-visible"></div>
			<div title="Voir les événements suivants" class="next {{ glyph('menu-right') }}"></div>
		</div>
		<h1>Événements à venir</h1>
		<ul class="block" id="event-list" data-nb="{{ $incomingEvents->count() }}">
		<?php $counter = 0; ?>
			@forelse($incomingEvents as $e)
			<div class="line {{ $counter++ > 4 ? 'not-displayed' : ''}}" id="{{$counter}}">
				<a title="Cliquez pour voir l'événement" href="{{ url('events/show/'.$e->slug) }}">
					<li>
						<div class="date">{{ $e->date->format('l d M Y').($e->city != '' ? ',': '') }}
							@if($e->city != '')
							 {!! $e->city !!}
							@endif
						</div>
						{{ ucfirst($e->name) }}
						<div class="chevron {!! glyph('menu-right') !!}"></div>
					</li>
				</a>
			</div>
			@empty	
			<li align="center">-</li>
			@endforelse		
		</ul>
	</div>

	<div class="jumbotron news">
		<h1>Dernières actualités</h1>
		<ul class="block">
		<?php $counter = 0; ?>
			@forelse($lastNews as $n)
			<div class="line">
				<a title="Cliquez pour consulter la news entièrement" href="{{ url('news/show/'.$n->slug) }}">
					<li>
						<div class="date">{{ $n->published_at->format('d M Y') }}</div>
						{{ ucfirst($n->title) }}
						<div class="chevron {!! glyph('menu-right') !!}"></div>
					</li>
				</a>
			</div>
			@empty	
			<li align="center">-</li>
			@endforelse		
		</ul>
	</div>
</div>

<footer>
	<p>Site réalisé par <b>R. Maréchal</b>.</p>
</footer>
    
	<script src="{{ URL::asset('vendor/js/file-input/plugins/canvas-to-blob.min.js')}}" ></script>
    <script src="{{ URL::asset('vendor/js/file-input/fileinput.min.js') }}" ></script>
    <script src="{{ URL::asset('vendor/js/file-input/fileinput_locale_fr.js') }}"></script>
    <script src="{{ URL::asset('vendor/js/recaptcha/recaptcha.min.js') }}"></script>
    <script src="{{ URL::asset('vendor/js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ URL::asset('vendor/js/timepicker/src/wickedpicker.js') }}"></script>
    <script src="{{ URL::asset('vendor/js/ckeditor/ckeditor.js') }}"></script>
	<script src="{{ URL::asset('vendor/js/bootbox.min.js')  }}"></script>
	<script src="{{ URL::asset('vendor/js/fileInput.js')  }}"></script>
	<script src="{{ URL::asset('vendor/js/modals.js')  }}"></script>
	<script src="{{ URL::asset('vendor/js/slideshow.js')  }}"></script>
	<script src="{{ URL::asset('vendor/js/home-events.js')  }}"></script>
	<script src="{{ URL::asset('vendor/js/jquery-visible/jquery.visible.min.js')  }}"></script>
	<script src="{{ URL::asset('vendor/js/wait.min.js')  }}"></script>
	<script type="text/javascript">
		$(document).scroll(function(){
			if(!$('#banner').visible( true )){
				$('#menu').addClass('menu-fixed');
				$('#menu').removeClass('menu-relative');
				$('#menu-replace').css('display', 'block').css('height', $('#menu').height );
			}else{
				$('#menu').removeClass('menu-fixed');
				$('#menu').addClass('menu-relative');
				$('#menu-replace').css('display', 'none').css('height', $('#menu').height );
			}	
		})
	</script>

	<script type="text/javascript">
		(function($){

			$('#preview').click(function(){
				var formID = $(this).parents('form').attr('id');
				var event = {};

				event.name 			= $('#' + formID + ' #name').val();
				event.date 			= $('#' + formID + ' #date').val();
				event.start 		= $('#' + formID + ' #start').val();
				event.end 			= $('#' + formID + ' #end').val();
				event.private 		= $('#' + formID + ' #private').is(':checked');
				event.city 			= $('#' + formID + ' #city').val();
				event.adress 		= $('#' + formID + ' #adress').val();
				event.description 	= CKEDITOR.instances.description.getData();

				$('#content-preview').remove();
				$('<div />', {class:'jumbotron', id: 'content-preview'}).load('../events/preview', function(){
					$('#pName').html(event.name);
					$('#pDate').html(event.date);
					$('#pStart').html(event.start);
					$('#pEnd').html(event.end);
					$('#pPrivate').html((event.private ? 'privé' : 'public'));
					$('#pCity').html(event.city);
					$('#pAdress').html(event.adress);
					$('#pDescription').html(event.description);
					
					$('html, body').animate({
				        scrollTop: $("#content-preview").offset().top
				    }, 500);
				}).insertAfter($('#content-jumbotron'));

			});
		})(jQuery);
	</script>

	<script type="text/javascript">
		$('#delete-event').click(function(){
			$('#modal-delete').modal('show');
		})
	</script>

	<script type="text/javascript">

		(function($){
			$('#alert').on('click', function(){
				$(this).animate({'opacity': '-1.2'}, 800 , function(){
					$(this).remove();
				});
			});
		})(jQuery);
	</script>



	<script type="text/javascript">
	var videoAdded = false;

		(function($){

			/**
			*	display a flash alert which fades out after 2s 
			*	@var msg
			*	@var type (default: "danger") : alert type
			*/
		    function flashMessage(msg, type="danger")
		    {
		    	var html = '<div title="Cliquez pour masquer le message" id="alert" class="alert js-alert alert-'+type+'">'+msg+'</div>';
		    	$('#alert').remove();
		    	$('body').append(html);
		    	$('#alert.js-alert').animate({'opacity': '+0.9'}, 0 );
		    	$('#alert.js-alert').delay(2000).animate({'opacity': '-1.2'}, 1000, function(){
					$(this).remove();
				});
		    }

		    function flashSuccess() 
		    {
		    	var html = '<div id="alert" class="js-alert-success alert-sucess"><span class="glyphicon glyphicon-ok flash"></span></div>';
		    	$('.js-alert-success').remove();
		    	$('body').append(html);
		    	$('#alert.js-alert-success').animate({'opacity': '+0.8'}, 350 );
		     	$('#alert.js-alert-success').delay(500).animate({'opacity': '-1.2'}, 550, function(){
					$(this).remove();
				});
		    }

		    function stop(el)
		    {
		    	el.animate({'opacity': '0'}, 700, function(){
					el.css('display', 'none');
		    	});
		    	$('.player').attr('src', '');
		    	$('.glyphicon-stop').removeClass('playing');
		    	$('.glyphicon-stop').removeClass('glyphicon-stop').addClass('glyphicon-play');
		    }

		    function isOwner(data)
		    {
		    	console.log(data);
		    	return data.playlists.event[0].user_id == data.user_id;
		    }

		    /**
		    *	Return number of likes and dislikes
		    *	@var data : request response
		    *	
		    *	@return array ['1' => nb, '-1' => nb]
		    */
		    function numberOfLikes(data)
		    {
		    	var array = [];
		    	array['1'] = 0
		    	array['-1'] = 0
		    	var nb = data.number_of_likes;

		    	for(var i=0; i<nb.length; i++)
		    	{
		    		var value = nb[i].value;
		    		array[''+value] += value > 0 ? value : -value;
		    	}

		    	return array;
		    }


		    /**
		    *	Check if a video is liked or disliked by current user
		    *	@var video
		    *	@var data : query response
		    *
		    *	@return boolean : liked || disliked
		    */
			function isLikedOrDisliked(video, data)
			{
				var array = [];
				array['value'] = false;
				array['1'] = 0;
				array['-1'] = 0;
				var user = data.user_id;

				var usersWhoLiked = video.users_who_liked;
				for(var j=0; j<usersWhoLiked.length; j++)
				{
					var liker = usersWhoLiked[j].user_id;
					var value = usersWhoLiked[j].value;
					if(user == liker)
					{
						array['value'] = value;
					}
					array[''+value] += value > 0 ? value : -value;
				}
				return array;
			}

			/**
			*	Check if the video is validated or refused by the event's owner
			*	@var video
			*
			*	@return boolean : validated || refused
			*/
			function isValidatedOfRefused(video)
			{ 
				if(video.validation == 1 || video.validation == -1){
					return video.validation;
				}
				return false;
			}

			/**
			*	Display the playlist's table on the page with pagination
			*	@var response : query response
			*	@var playlist_id
			*/
			function printPlaylistTable(response, playlist_id, styles='')
			{
				var data = response.videos;
				var videos = response.videos.data;
				var playlist = '';

				$('#event-playlist').html('<div class="col-lg-offset-1 col-lg-10"></div>');


				// add music
				playlist += '<div class="add-music row">';
				playlist += '<h2 align="center"><a title="Cliquez ici pour voir la playlist avec plus de fonctionnalités (Ex : lecture auto, trier, etc...)" href="/playlists/show/'+playlist_id+'">'+ response.playlists.name +'</a>';
				if(isOwner(response))
				{
					playlist+= ' 	<button title="Supprimer la playlist" target="modal-delete-playlist" id="delete-playlist" data-playlist="'+response.playlists.id+'" class="glyphicon glyphicon-trash"></button>';
				}
				playlist += '</h2>';
				playlist += 	'<p class="playlist-infos" align="center"><span id="playlist-styles">'+ styles +'</span> </p>';
				playlist += 	'<p class="playlist-infos" align="center">Musiques : <span id="nb-of-musics">'+ response.videos.total +'</span> </p>';
				// if(isOwner(response))
				// {
				// 	playlist += '<p align="center">Vous estimez que cette playlist est prête ? </p>';
				// 	playlist += '<p align="center"><a href="/playlists/export/'+playlist_id+'"><button class="btn btn-primary">Exporter vers YouTube</button></a></p>';
				// }
				playlist += 	'<h3 align="center">Ajouter une musique :</h3>';
				playlist += 	'<form class="form form-inline" method="POST" id="add-music" action="/playlists/addmusic">';
				playlist += 		'<input hidden name="playlist_id" value="'+playlist_id+'"/>';
				playlist += 		'<div class="col-lg-3"><input required class="form-control" type="text" id="artist" name="artist" placeholder="Nom de l\'artiste ou du groupe" /></div>';
				playlist += 		'<div class="col-lg-3"><input required class="form-control" type="text" id="name" name="name" placeholder="Titre du morceau" /></div>';
				playlist += 		'<div class="col-lg-3"><input required class="form-control" type="text" id="link" name="link" placeholder="Lien youtube" /></div>';
				playlist += 		'<div class="col-lg-3"><button type="submit" class="btn btn-primary">Valider</button></div>';
				playlist += 	'</form>';
				playlist += '</div>';

				playlist += '<table class="table table-hover" id="playlist-music-addeds">';
				playlist +=		'<thead>';
				playlist +=			'<tr>';
				// playlist +=				'<td> Utilisateur </td>';
				playlist +=				'<td> Artiste - Titre </td>';
				playlist +=				'<td align="center" width="50px"> Contrôles </td>';
				playlist +=				'<td align="center" width="150px"> Likes/Dislikes </td>';
				playlist +=				'<td align="center" width="150px"> Validation </td>';
				playlist +=			'</tr>';
				playlist +=		'</thead>';
				playlist +=		'<tbody>';
				playlist += 		'<tr id="default-line">';
				playlist += 			'<td>-</td> <td align="center">-</td> <td align="center">-</td> <td align="center">-</td>';
				playlist += 		'</tr>';
				playlist +=		'</tbody>';
				playlist +=	'</table>';

				playlist +=		'<br />';
				playlist +=	'<h3 align="center">Les autres musiques :</h3>';
				playlist += '<table class="table table-hover" id="playlist">';
				playlist +=		'<thead>';
				playlist +=			'<tr>';
				// playlist +=				'<td> Utilisateur </td>';
				playlist +=				'<td> Artiste - Titre </td>';
				playlist +=				'<td align="center" width="50px"> Contrôles </td>';
				playlist +=				'<td align="center" width="150px"> Likes/Dislikes </td>';
				playlist +=				'<td align="center" width="150px"> Validation </td>';
				playlist +=			'</tr>';
				playlist +=		'</thead>';
				playlist +=		'<tbody>';

				for(var i = 0; i<videos.length; i++)
				{
					var liked = false;
					var disliked = false;
					var validated = false;
					var refused = false;
					var user_id = response.user_id;

					var likesOrDislikes = isLikedOrDisliked(videos[i], response);

					if(user_id != 'null')
					{
						var mind = likesOrDislikes['value'];
						if(mind != false)
						{
							liked = mind == 1;
							disliked = mind == -1;
						}
					}

					var validation = isValidatedOfRefused(videos[i]);
					if(validation != false)
					{
						validated = validation == 1;
						refused = validation == -1;
					}

					var likes = likesOrDislikes['1'];
					var dislikes = likesOrDislikes['-1'];

					var v = videos[i];
					playlist +=		'<tr id="'+ v.id +'">';
					// playlist +=			'<td> '+ /*v.user.first_name +' '+ v.user.last_name */ '' +' </td>';
					playlist +=			'<td> <a target="_BLANK" data-video="'+v.video.url+'" href="https://youtu.be/'+ v.video.url +'">'+ v.video.artist + ' - ' + v.video.name +'</a>  </td>';
					playlist +=			'<td align="center"> <button title="Play/Stop" class="glyphicon glyphicon-play play"></button> </td>';
					playlist +=			'<td align="center"> ';
					if(response.user_id > 0)
					{
						playlist +=			'<button class="glyphicon glyphicon-thumbs-up like '+(user_id == 'null' ? 'disabled' : liked ? 'colored' : '' )+'">';
						playlist +=				'<span class="likes">'+likes+'</span>';
						playlist += 		'</button>';
						playlist +=			'<button class="glyphicon glyphicon-thumbs-down dislike '+(user_id == 'null' ? 'disabled' : disliked ? 'colored' : '' )+'">';
						playlist +=				'<span class="dislikes">'+dislikes+'</span>';
						playlist +=			'</button>';
					}
					else
					{
						playlist +=			'<span class="glyphicon glyphicon-thumbs-up like '+(user_id == 'null' ? 'disabled' : liked ? 'colored' : '' )+'">';
						playlist +=				'<span class="likes">'+likes+'</span>';
						playlist += 		'</span>';
						playlist +=			'<span class="glyphicon glyphicon-thumbs-down dislike '+(user_id == 'null' ? 'disabled' : disliked ? 'colored' : '' )+'">';
						playlist +=				'<span class="dislikes">'+dislikes+'</span>';
						playlist +=			'</span>';
					}
					playlist +=			'</td>';
					playlist +=			'<td align="center">';

					if(user_id == response.playlists.event[0].user_id)
					{
						playlist += 		'<button class="glyphicon glyphicon-ok validate '+(validated ? 'colored' : '' )+'"></button>';
						playlist += 		'<button class="glyphicon glyphicon-remove refuse '+(refused ? 'colored' : '' )+'"></button> ';
					}
					else
					{
						playlist += 		'<span class="glyphicon glyphicon-ok validate '+(validated ? 'colored' : '' )+'"></span>';
						playlist += 		'<span class="glyphicon glyphicon-remove refuse '+(refused ? 'colored' : '' )+'"></span> ';
					}

					playlist += 		'</td>';
					playlist +=		'</tr>';
				}

				playlist +=		'</tbody>';
				playlist +=	'</table>'

				$('#event-playlist>div').html(playlist);

				if(data.last_page > 1)
				{
					$('#event-playlist>div').append('<div class="paginator row" align="right"></div>');
					var pagination = '';
					pagination += '<div align="col-lg-12 right">';
					pagination += 	'<ul class="pagination">';
					pagination += 		'<li'+ (1 == data['from'] ? ' class="disabled">' : '> <a href="/playlists/'+playlist_id+'?page='+(data['from']-1)+'">') +'<span> «'+ (0 != data['from'] ? '</a>' : '')+'</span></li>';					for(var i=1; i<=data.last_page; i++)
					{
						pagination += 		'<li'+ (i == data['from'] ? ' class="active">' : '> <a href="/playlists/'+playlist_id+'?page='+i+'">') +'<span>'+ i + (i != data['from'] ? '</a>' : '')+'</span></li>';
					}
					pagination += 		'<li'+ (data.last_page == data['from'] ? ' class="disabled">' : '> <a href="/playlists/'+playlist_id+'?page='+(data['from']+1)+'">') +'<span> »'+ (data.last_page != data['from'] ? '</a>' : '')+'</span></li>';
					pagination +=	'</ul>';
					pagination += '</div>';

					$('#event-playlist .paginator').html(pagination);
				}

			}

			/**
			*	Send a GET request to the server to get playlist data
			*	@var url
			*/
			function getPlaylist(url)
			{
				$.get(url, function(data)
					{
						var videos = data.videos.data;

						var arrayStyles = data.playlists.styles;
						var styles = arrayStyles[0].name;
						for(var i=1; i<arrayStyles.length; i++)
						{
							styles += ', '+arrayStyles[i].name;
						}

			    		printPlaylistTable(data, data.playlists.id, styles);

			    		$('#event-playlist').animate({'opacity': '1'}, 500);

						$('#delete-playlist').click(function(){
							var target = '#'+$(this).attr('target');
							var id = $(this).attr('data-playlist');
							var date = data.playlists.created_at;
							var dates = date.split(' ');
							var time = dates[1].split(':');
							date = dates[0].replace(/-/g, '/') + ' à ' + time[0]+':'+time[1];


							$(target +' #playlist_id').attr('value', id);
							$(target +' p#name span').html(data.playlists.name);
							$(target +' p#date span').html(date);
							$(target +' p#name span').html(data.playlists.name);
							$(target +' p#styles span').html(styles);
							$(target +' p#nb-musics span').html(data.videos.total);

							$(target).modal('show');
						});

						$('.pagination a').on('click', function(e){
						    e.preventDefault();
						    var url = $(this).attr('href');
						    getPlaylist(url);
						});

					    $('.like, .dislike').click(function(){
					    	if(videoAdded == false)
					    	{
						    	if($(this).hasClass('disabled'))
						    	{
						    		flashMessage('Vous devez être connecté pour pouvoir donner votre avis.');
						    	}else{
						    		var el 		= $(this);
						    		var value 	= el.hasClass('like') ? 1 : -1;
						    		var tr 		= el.parents('tr')
						    		var id 		= tr.attr('id');

							    	$.ajax({
								      	url: '/playlists/likeordislike',
								      	type: 'post',
								      	data: {
						    				_token: $('meta[name=csrf-token]').attr('content'),
						    				value: value,
						    				playlist_video_id: id 
								      	},
								      	success: function(data){
								      		flashSuccess();
								      		var wasColored = el.hasClass('colored');
								      		$('tr#'+id+' .like').removeClass('colored');
								      		$('tr#'+id+' .dislike').removeClass('colored');

								      		var array = numberOfLikes(data);
								      		$('tr#'+id+' .likes').html(array['1']);
								      		$('tr#'+id+' .dislikes').html(array['-1']);

								      		if(wasColored == false)
								      		{
									      		el.addClass('colored');
								      		}
								      	},
								      	error: function(data){
								      		flashMessage('Une erreur est survenue, votre avis n\'a pas été enregistré. Actualisez la page et réessayez.');
								      	}
								    }); 
						    	}
					    	}
					    });

					    $('.validate, .refuse').click(function(){
					    	if(isOwner(data))
					    	{
						    	if(videoAdded == false)
						    	{
							    	var el 		= $(this);
						    		var value 	= el.hasClass('validate') ? 1 : -1;
						    		var tr 		= el.parents('tr')
						    		var id 		= tr.attr('id');

							    	$.ajax({
								      	url: '/playlists/validation',
								      	type: 'post',
								      	data: {
						    				_token: $('meta[name=csrf-token]').attr('content'),
						    				value: value,
						    				playlist_video_id: id 
								      	},
								      	success: function(data){
								      		flashSuccess();
								      		var wasColored = el.hasClass('colored');
								      		$('tr#'+id+' .validate').removeClass('colored');
								      		$('tr#'+id+' .refuse').removeClass('colored');

								      		if(wasColored == false)
								      		{
									      		el.addClass('colored');
								      		}
								      	},
								      	error: function(data){
								      		flashMessage('Une erreur est survenue, votre avis n\'a pas été enregistré. Actualisez la page et réessayez.');
								      	}
								    }); 
							    }
					    	}
					    });
					   	// PLAYER
					    $('button.play').click(	function()
					    {	
						    if(videoAdded == false)
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
					    	}	
					    });

					    $('#add-music').submit(function(e){
					    	e.preventDefault();
					    	$('#add-music #link').parent().removeClass('has-error');

					    	var form = $(this);
					    	var url = form.attr('action');

					    	var playlist_id = form.children('input[name=playlist_id]').val();
					    	var artist = $('#add-music #artist').val();
					    	var name = $('#add-music #name').val();
					    	var link = $('#add-music #link').val();

					    	var embed = '';

					    	var regexp = new RegExp(/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/);
					    	var test = regexp.test(link);

					    	if(test == false)
					    	{
					    		flashMessage("L'url renseignée est incorrecte.");
					    		$('#add-music #link').parent().addClass('has-error');
					    	}else{
					    		$.ajax({
						      	url: url,
						      	type: 'post',
						      	data: {
				    				_token: $('meta[name=csrf-token]').attr('content'),
				    				artist: artist,
				    				playlist_id: playlist_id,
				    				name: name,
				    				link: link 
						      	},
						      	success: function(data){
						      		if(data == false)
						      		{
						      			flashMessage('Cette musique a déjà été ajoutée  à cette playlist.');
						      		}else{
						      			flashSuccess();

						      			videoAdded = true;

							      		line = '';
							      		var exists = $('#playlist-music-addeds').length != 0;

							      		line +=	'<tr id="'+data.id+'">';
										line +=		'<td> <a target="_BLANK" data-video="'+data.video.url+'" href="'+link+'">'+ artist + ' - ' + name +'</a>  </td>';
										line +=		'<td align="center"> <button class="glyphicon glyphicon-play play"></button> </td>';
										line +=		'<td align="center"> <button class="glyphicon glyphicon-thumbs-up like "><span class="likes">0</span></button><button class="glyphicon glyphicon-thumbs-down dislike"><span class="dislikes">0</span></button> </td>';
										line +=		'<td align="center"><button class="glyphicon glyphicon-ok validate"></button><button class="glyphicon glyphicon-remove refuse "></button> </td>';
										line +=	'</tr>';

										$('#default-line').remove();
										$('#playlist-music-addeds tbody').prepend(line);

										$('#add-music input').val('');

										$('#nb-of-musics').html(parseInt($('#nb-of-musics').text())+1);

					    				$('button.play').click(	function(){
										    if(!$(this).hasClass('playing'))
										    {
						    					$('.glyphicon-stop').removeClass('glyphicon-stop').addClass('glyphicon-play');
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

									    $('.like, .dislike').click(function(){
									    	if($(this).hasClass('disabled'))
									    	{
									    		flashMessage('Vous devez être connecté pour pouvoir donner votre avis.');
									    	}else{
									    		var el 		= $(this);
									    		var value 	= el.hasClass('like') ? 1 : -1;
									    		var tr 		= el.parents('tr')
									    		var id 		= tr.attr('id');

										    	$.ajax({
											      	url: '/playlists/likeordislike',
											      	type: 'post',
											      	data: {
									    				_token: $('meta[name=csrf-token]').attr('content'),
									    				value: value,
									    				playlist_video_id: id 
											      	},
											      	success: function(data){
											      		flashSuccess();
											      		var wasColored = el.hasClass('colored');
											      		$('tr#'+id+' .like').removeClass('colored');
											      		$('tr#'+id+' .dislike').removeClass('colored');

											      		var array = numberOfLikes(data);
											      		$('tr#'+id+' .likes').html(array['1']);
											      		$('tr#'+id+' .dislikes').html(array['-1']);

											      		if(wasColored == false)
											      		{
												      		el.addClass('colored');
											      		}
											      	},
											      	error: function(data){
											      		flashMessage('Une erreur est survenue, votre avis n\'a pas été enregistré. Actualisez la page et réessayez.');
											      	}
											    }); 
									    	}
									    });

									    $('.validate, .refuse').click(function(){
									    	if(isOwner(data))
					    					{
										    	var el 		= $(this);
									    		var value 	= el.hasClass('validate') ? 1 : -1;
									    		var tr 		= el.parents('tr')
									    		var id 		= tr.attr('id');

										    	$.ajax({
											      	url: '/playlists/validation',
											      	type: 'post',
											      	data: {
									    				_token: $('meta[name=csrf-token]').attr('content'),
									    				value: value,
									    				playlist_video_id: id 
											      	},
											      	success: function(data){
											      		flashSuccess();
											      		var wasColored = el.hasClass('colored');
											      		$('tr#'+id+' .validate').removeClass('colored');
											      		$('tr#'+id+' .refuse').removeClass('colored');

											      		if(wasColored == false)
											      		{
												      		el.addClass('colored');
											      		}
											      	},
											      	error: function(data){
											      		flashMessage('Une erreur est survenue, votre avis n\'a pas été enregistré. Actualisez la page et réessayez.');
											      	}
											    }); 
										    }
									    });
						      		}
						      	},
						      	error: function(data){
						      		flashMessage('Une erreur est survenue, impossible d\'ajouter votre musique.');
						      	}
						    }); 
					    	}

					    });
					});
			}

			// Display the first playlist at the page loading
			var playlist = $('#playlists>h1>nav>li.menu-playlist:first-child');
			if(playlist.length > 0)
			{
				var playlist_id = playlist.attr('target');
				var path = '/playlists/'+playlist_id;
				getPlaylist(path);
			}

			// Display another playlist when one of the other buttons are triggered
		    $('li.menu-playlist').click(function(el){
		    	var element = $(this);
		    	if(!element.hasClass('active'))
		    	{
			       	$('.playlist-nav>li.active').removeClass('active');
			       	element.addClass('active');
			    	var playlist_id = $(this).attr('target');
			    	$('#event-playlist').animate({'opacity': '0'}, 500);
			    	getPlaylist('/playlists/'+playlist_id);

		    	};
		    });

		    $('.iframe-close').click(function()
		    {
		    	var el = $(this);
		    	var parent = el.parents('div.iframe-div')
		    	stop(parent);
		    });

			function toggleView(elementStr, little)
			{
				var el = $(elementStr);
				if(little)
				{
					el.addClass('little');
					el.removeClass('big');
					$('#iframe .toggle-view.glyphicon-menu-down').addClass('glyphicon-menu-up');
					$('#iframe .toggle-view.glyphicon-menu-down').removeClass('glyphicon-menu-down');
					$('#iframe .toggle-view').attr('title', 'Agrandir le cadre');
				}else
				{
					el.removeClass('little');
					el.addClass('big');
					$('#iframe .toggle-view.glyphicon-menu-up').addClass('glyphicon-menu-down');
					$('#iframe .toggle-view.glyphicon-menu-up').removeClass('glyphicon-menu-up');
					$('#iframe .toggle-view').attr('title', 'Réduire le cadre');
				}
			}	  

		    var little = false;
			$('#iframe .toggle-view').click(function(){
				var elementStr = $('#iframe');
				little = !little;
				toggleView(elementStr, little);
			})

		})(jQuery);
	</script>

	<script type="text/javascript">
		$('#add-playlist').click(function(){
			$('#modal-add-playlist').modal('show');
		});

		// Close modal on ESCAPE key pressed event
		$(document).on('keyup',function(evt) {
		    if (evt.keyCode == 27) {
		       $('.modal').modal('hide');
		    }
		});
	</script>

	<script type="text/javascript">
	 	var selectAll = false;
		$('#select-all').click(function(){
			var select = $(this).parents('select');
			if(selectAll == false)
			{
				select.children('option:not(:first-child)').prop('selected', true);
			}else{
				select.children('option:not(:first-child)').prop('selected', false);
			}
			selectAll = !selectAll;
		})

		$('select option:not(#select-all)').click(function(){
			if(selectAll == true)
			{
				var select = $(this).parents('select');
				selectAll = false;
				select.children('option:not(:first-child)').prop('selected', false);
				$(this).prop('selected', true);
			}	
		});
	</script>

	@yield('js')

</body>
</html>