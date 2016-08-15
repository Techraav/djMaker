@extends('layouts.app')

@section('title')
	Test
@stop

@section('content')

	<div class="jumbotron" style="padding-right:90px">
		<form id="form" method="post" action="">

		{{ csrf_field() }}
		  <fieldset>
		    <h1 style="margin-top:0"align="center">Test ajax</h1>
		    <br />
		    <div class="form-group">
		      <label for="name" class="col-lg-2 control-label">Nom</label>
		      <div class="col-lg-10">
		        <input type="text" class="form-control" name="name" id="name" placeholder="Nom de l'événement">
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="summary" class="col-lg-2 control-label">Description</label>
		      <div class="col-lg-10">
		        <textarea class="form-control" rows="3" id="summary" name="summary"></textarea>
		        <span class="help-block">Décrivez votre événement pour les autres utilisateurs</span>
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="textArea" class="col-lg-2 control-label">Style(s) de musique recherché(s)</label>
		      <div class="col-lg-10">
		        <textarea class="form-control" rows="3" id="style" name="style">Exemple : Rock, Hard Rock, Metal...</textarea>
		        <span class="help-block">Indiquez ici aux autre utilisateurs quel(s) style(s) de musique vous recherchez.</span>
		        <div class="checkbox">
		          <label>
		            <input type="checkbox" name="private" id="private"> Événement privé
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

		<div class="row col-lg-5">
			<h2>Get Request</h2>
			<button type="button" class="btn btn-warning" id="getRequest">getRequest</button>
		</div>

		<div id="getRequestData"></div>

		<div id="postRequestData"></div>
	</div>
@stop

@section('js')

	<script type="text/javascript">
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$(document).ready(function(){
		$('#getRequest').click(function()
		{
			$.get('test/getRequest', function(data){
				$('#getRequestData').append(data);
				console.log(data);
			});
		});

		$('#form').submit(function()
		{
			var name = $('#name').val();
			var summ = $('#summary').val();

			$.post('form', { name:name, summary:summ}, function(data){
				console.log(data);
				$('#postRequestData').html(data);
			});
		});
	});
	</script>

@stop

