@extends('layouts.app')

@section('title')
	Connexion
@stop

@section('content')
	<h1>Connexion</h1>
	<hr />
	<div class="form-content row" align="center">
		<div class="facebook-login">
			<a href="{{ url('auth/facebook') }}"><button class="btn-fb btn"><img src="{{ url('vendor/img/fb.png') }}"><p>Se connecter via Facebook</p></button></a>
		</div>
		<div class="basic-login">
			<form class="login form" action="{{ url('login') }}" method="POST">
			{{ csrf_field() }}
				<fieldset class="col-lg-6 col-lg-offset-3">
					<legend>Ou</legend>
					
					<div class="row">
						<div class="form-group-lg form-group col-lg-10	col-lg-offset-1">
							<input type="text" name="email" class="form-control" placeholder="Adresse email">
						</div>

						<div class="form-group-lg form-group col-lg-10	col-lg-offset-1">
							<input type="password" name="password" class="form-control" placeholder="Mot de passe">
						</div>

						{!! app('captcha')->display(); !!}

					</div>	

					<div class="buttons row">
						<a href="{{ url('register') }}" class="form-option">Vous n'avez pas encore de compte ? Créez-en un maintenant !</a>
						<button type="submit" class="btn-primary btn">Se connecter</button>
					</div>
				</fieldset>
			</form>
		</div>
	</div>

	
@stop