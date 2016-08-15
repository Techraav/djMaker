@extends('layouts.app')

@section('title')
	Créer un événement
@stop

@section('content')
	<h1>Créer un événement</h1>
	<hr />
	<h2 align="center"> Veuillez remplir ce formulaire :</h2>
	<br />
	<div class="row">
		<form id="create-event-form" class="form col-lg-8 col-lg-offset-2" method="POST" action="{{ url('events/create') }}">
			{!! csrf_field() !!}
			<div class="form-group @if($errors->has('name')) has-error @endif">
				<label class="required control-label">Nom de l'événement :</label>
				<input required class="form-control" type="text" name="name" id="name" placeholder="Nom de l'événement" value="{{ old('name') }}">
			</div>
			
			<div class="form-group @if($errors->has('date')) has-error @endif">
				<label class="required control-label">Date de l'événement :</label>
				<input required class="form-control" type="text" id="date" name="date" id="date" placeholder="Sélectionnez la date" value="{{ old('date') }}">
			</div>
			
			<div class="form-group @if($errors->has('start')) has-error @endif">
				<label class="required control-label">Horaire de début :</label>
				<input required class="form-control" type="text" id="time-sart" name="start" id="start" placeholder="Sélectionnez un horaire de début" value="{{ old('start') }}">
			</div>
			
			<div class="form-group @if($errors->has('end')) has-error @endif">
				<label class="required control-label">Horaire de fin :</label>
				<input required class="form-control" type="text" id="time-end" name="end" id="end" placeholder="Sélectionnez un horaire de fin" value="{{ old('end') }}">
			</div>
			
			<div class="form-group">
				<div class="checkbox">
		          	<label title="Un événement privé n'est accessible que par ceux qui en possèdent le lien.">
		            	 <input type="checkbox" name="private" id="private"> &nbsp; Événement privé
		          	</label>
        		</div>
			</div>
			
			<div class="form-group @if($errors->has('city')) has-error @endif">
				<label class="required control-label">Lieu de l'événement :</label>
				<input required class="form-control" type="text" name="city" id="city" placeholder="Ville ou ville la plus proche" value="{{ old('city') }}">
			</div>
			
			<div class="form-group @if($errors->has('adress')) has-error @endif">
				<label class="control-label">Adresse :</label>
				<input class="form-control" type="text" name="adress" id="adress" placeholder="Entrez le n° et la voie" value="{{ old('adress') }}">
				<span class="info"><i>Exemple : 6 rue des Colombes.</i></span>
			</div>
			
			<div class="form-group @if($errors->has('description')) has-error @endif">
				<label>Description et informations complémentaires :</label>
				<textarea name="description" id="description" placeholder=""> {{ old('description') }}</textarea>
			</div>

			<p class="info requimred"><i>= champs obligatoires.</i></p>

			<div class="row buttons" align="center">
				<button id="preview" class="btn-primary btn" type="button">Prévisualiser</button>
				<button type="submit" class="btn-primary btn">Valider et créer l'événement !</button>
			</div>
		</form>
	</div>

@stop

@section('js')
	<script type="text/javascript">
		$('#date').datepicker();
		$('#time-sart').wickedpicker();
		$('#time-end').wickedpicker();
		CKEDITOR.replace('description');
	</script>
@stop