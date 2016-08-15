@extends('layouts.app')

@section('title')
	{{ $event['name'] }}
@stop

@section('content')

	<div class="jumbotron" style="padding-right:90px">
		<form class="form-horizontal" method="post">

		{{ csrf_field() }}
		  <fieldset>
		    <h1 style="margin-top:0"align="center">Modifier l'événenement</h1>
		    @if($errors->any())
		    	@include('errors.forms', $errors->all())
		    @endif		    <br />
		    <div class="form-group">
		      <label for="name" class="col-lg-2 control-label">Nom</label>
		      <div class="col-lg-10">
		        <input type="text" class="form-control" name="name" id="name" value="{{ $event['name'] }}">
		      </div>
		    </div>
		    <div class="form-group">
		    	<label for="date" class="col-lg-2 control-label">Date de l'événement</label>
		    	<div class="col-lg-10">
		    		<input type="date" name="date" id="date" required placeholder="Date prévue pour l'événement" value=" {{ $event['date'] }}" class="form-control"></input>
		    	</div>
		    </div>	
		    <div class="form-group">
		      <label for="description" class="col-lg-2 control-label">Description</label>
		      <div class="col-lg-10">
		        <textarea class="form-control" rows="3" id="description" name="description">{{ $event['description'] }}</textarea>
		        <span class="help-block">Décrivez votre événement pour les autres utilisateurs</span>
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="textArea" class="col-lg-2 control-label">Style(s) de musique recherché(s)</label>
		      <div class="col-lg-10">
		        <textarea class="form-control" rows="3" id="style" name="style">{{ $event['style'] }}</textarea>
		        <span class="help-block">Indiquez ici aux autre utilisateurs quel(s) style(s) de musique vous recherchez.</span>
		        <div class="checkbox">
		          <label>
		            <input type="checkbox" name="private" id="private" @if($event['private'] == 1) checked @endif > Événement privé
		          </label>
		        </div>
		      </div>
		    </div>
		    <div class="form-group">
		      <div class="col-lg-10 col-lg-offset-2">
		        <button type="reset" class="btn btn-default">Réinitialiser</button>
		        <button type="submit" class="btn btn-primary">Valider</button>
		      </div>
		    </div>
		  </fieldset>
		</form>
	</div>

@stop