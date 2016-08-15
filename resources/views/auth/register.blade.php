@extends('layouts.app')

@section('title')
	Inscription
@stop

@section('content')

<?php
	if(Request::get('action') == 'link')
	{
		if(old('email') == '' || old('first_name') == '' || old('last_name') == '' )
		{
			header('location: http://djmaker.fr/register');
			exit;
		}
	}
?>
	<h1>Inscription</h1>

	<hr />

	<div class="form-content row" align="center">
		<div class="facebook-login">
			<a href="{{ url('auth/facebook') }}"><button class="btn-fb btn"><img src="{{ url('vendor/img/fb.png') }}"><p>Se connecter via Facebook</p></button></a>
		</div>
		<div class="basic-login">
			<form class="login form" action="{{ url('register') }}" method="POST">
			{{ csrf_field() }}
				<fieldset class="col-lg-6 col-lg-offset-3">
					<legend>Ou</legend>
					
					<div class="row">
						<div class="form-group-lg form-group col-lg-10	col-lg-offset-1 {{ $errors->has('first_name') ? 'has-error' : ''}}" {{ $errors->has('first_name') ? 'has-error' : ''}}>
							<input type="text" name="first_name" class="form-control" placeholder="Prénom" value="{{ old('first_name') }}">
						</div>

						<div class="form-group-lg form-group col-lg-10	col-lg-offset-1 {{ $errors->has('last_name') ? 'has-error' : ''}}">
							<input type="text" name="last_name" class="form-control" placeholder="Nom" value="{{ old('last_name') }}">
						</div>

						<div class="form-group-lg form-group col-lg-10	col-lg-offset-1 {{ $errors->has('email') ? 'has-error' : ''}}">
							<input type="text" name="email" class="form-control" placeholder="Adresse email" value="{{ old('email') }}">
						</div>

						<div class="form-group-lg form-group col-lg-10	col-lg-offset-1 {{ $errors->has('password') ? 'has-error' : ''}}">
							<input type="password" name="password" class="form-control" placeholder="Mot de passe" value="{{ old('password') }}">
						</div>

						<div class="form-group-lg form-group col-lg-10	col-lg-offset-1 {{ $errors->has('password_confirmation') ? 'has-error' : ''}}">
							<input type="password" name="password_confirmation" class="form-control" placeholder="Confirmez le mot de passe" value="{{ old('password_confirmation') }}">
						</div>

						{!! app('captcha')->display(); !!}


					</div>	

					<div class="buttons row">
						<a href="{{ url('login') }}" class="form-option">Vous avez déja un compte ? Connectez vous !</a>
						<button type="submit" class="btn-primary btn">S'inscrire</button>
					</div>
				</fieldset>
			</form>
		</div>
	</div>

	<div @if(Request::get('action') == 'link') id="link-user" @endif class="modal fade" id="modalControl" role="dialog">
		<div class="modal-dialog">

	  	<!-- Modal content-->
	      	<div class="modal-content">
	       	 	<div class="modal-header">
	          		<button type="button" class="close" data-dismiss="modal">&times;</button>
	          		<h4 id="modal-title" class="modal-title">Liez votre compte avec Facebook</h4>
	        	</div>

	        	<div class="row">
		        	<div class="col-lg-8 col-lg-offset-2">
			        	<p>Nous avons détecté un compte possédant les informations suivantes : </p>
			        	<p>Nom : <b>{{ ucfirst(old('first_name')) }} {{ ucfirst(old('last_name')) }}</b></p>
			        	<p>Adresse email : <b>{{ old('email') }}</b></p>
			        	<br />
			        	<p>Voulez-vous lier les deux comptes ?</p>
		        	</div>
	        	</div>

		        <form id="control-form" class="modal-form" method="post" action="{{ url('auth/link') }}">
		        {!! csrf_field() !!}
			        <div class="modal-body">
			        <input hidden name="email" value="{{ old('email') }}">
			        <input hidden name="password" value="{{ Hash::make(old('password')) }}">
			        </div>
			        <div class="modal-footer">
			          	<button type="button" class="btn btn-default" data-dismiss="modal">Non merci.</button>
			          	<button type="submit" class="btn btn-primary">Oui, lier les comptes.</button>
			        </div>
				</form>

	   		</div>
		</div>
	</div>


<script>
	(function($){
		$('#link-user').modal('show');
	})(jQuery);
</script>
	
@stop