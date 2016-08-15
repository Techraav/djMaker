@extends('layouts.app')

@section('breadcrumb')
	<li>Test</li>
@stop

@section('content')

<?php

    // mail('robin-marecham@hotmail.fr', 'test djmaker', 'ceci est un test');

?>

    <h1>Bienvenue sur DjMaker !</h1>
    <hr />
    {{-- <p>Préparez vos soirées en créant des playlists avec l'aide de votre entourage </p> --}}
    {{-- <p>Pourquoi créer tout seul des playlists que les autres écouteront ?</p> --}}
    <p>Parce que la musique est essentielle pour passer une bonne soirée, <b>DjMaker</b> vous propose {{-- la --}} une solution collaborative pour créer des playlists qui plairont à tout le monde ! </p>
{{--     <p>Créer des playlists qui plaisent à tout le monde pour ses futurs événements ? C'est maintenant possible gràce à DjMaker !</p>
    <p>En 3 clics, créez vos évènements et vos playlists, demandez à votre entourage de proposer des musique et de donner leur avis.</p>
    <p>Lorsque que vos playlists dont prêtes, exportez les directement sur YouTube !</p>
 --}}    <hr />
    <div class="row logos">
    	<div class="col-sm-3 logo1">
    		<a title="Cliquez pour en savoir plus" class="box-logo" href="{{ url('about/#playlist') }}">
    			<div class="logo">
    				<img alt="logo playlist" src="{{ url('vendor/img/playlist.png') }}">
    			</div>
    			<h1>Créez vos événements et vos playlists</h1>
    		</a>
    	</div>
    	<div class="col-sm-3 logo2">
    		<a title="Cliquez pour en savoir plus" class="box-logo" href="{{ url('about/#private') }}">
    			<div class="logo">
    				<img alt="logo private" src="{{ url('vendor/img/private.png') }}">
    			</div>
    			<h1>Rendez les publiques ou privées</h1>    			
    		</a>
    	</div>
    	<div class="col-sm-3 logo3">
    		<a title="Cliquez pour en savoir plus" class="box-logo" href="{{ url('about/#friends') }}">
    			<div class="logo">
    				<img alt="logo friends" src="{{ url('vendor/img/friends.png') }}">
    			</div>
    			<h1>Demandez à vos amis d'y ajouter des morceaux</h1>
    		</a>
    	</div>
    	<div class="col-sm-3 logo4">
    		<a title="Cliquez pour en savoir plus" class="box-logo" href="{{ url('about/#manage') }}">
    			<div class="logo">
    				<img alt="logo manage" src="{{ url('vendor/img/manage.png') }}">
    			</div>
    			<h1>Gérez les musiques proposées</h1>
    		</a>
    	</div>
    </div>
    <hr />

	    <p>DjMaker est un outil de création de playlists collaboratif.</p>
	    <p>Créez votre événement, et partagez-le autour de vous pour permettre à d'autres de la remplir.</p>
	    <p>Il est nécéssaire d'être connecté pour créer un événement, mais pas pour proposer des musiques ! </p>
        <p>Les membres connectés peuvent cependant donner leur avis sur les musiques avec les boutons like et dislike.</p>
	    @if(Auth::check())
	    	<p>Vous souhaitez créer une playlist ? Commencez par créer un événement en <a href="{{ url('events/create') }}"><b>cliquant ici</b></a> !</p>
	    @else
	    	<p>Vous souhaitez créer une playlist ? Commencez par vous <a href="{{ url('login') }}"><b>connecter</b></a> ou vous <a href="{{ url('register') }}"><b>inscrire</b></a> !</p>
	    @endif
@stop