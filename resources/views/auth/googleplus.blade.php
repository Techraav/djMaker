@extends('layouts.app')

@section('title')
	Lier son compte avec Google+
@stop

@section('content')
	<h1>Lier son compte avec avec Google+</h1>
	<hr />

	<p>Pour créer un événement, nous avons besoin d'accéder à vos informations YouTube. </p>
	<p>Pour cela nous vous demandons de lier votre compte avec Google+.</p>
	<p>Cela ne nous permettra uniquement de créer des playlists YouTube, en aucun cas de modifier les informations et les données de votre compte.</p>
	<hr />
	{{-- <p>Pour lier votre compte, cliquez <a href="{{ url('auth/google') }}"><b>ici</b></a>.</p> --}}
	<a href="{{ url('auth/google') }}"><button class="btn btn-google btn-fb"><img src="{{ url('vendor/css/google.png') }}">Lier son compte</button></a>
@stop