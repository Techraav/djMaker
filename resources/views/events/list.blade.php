@extends('layouts.app')

@section('title')
	Liste des événements publiques
@stop 

@section('content')
	<h1 align="center"> Liste des événements publiques </h1>

	@if($filter != '')
		<h4 align='center'>Filtre : {{ $filter }}</h4>
	@endif

	<form> 
		
		<div class="form-group filter">
                <select class="form-control" name="filter" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                	<option selected disabled>Choisissez un filtre...</option>
                	<option value="?filter=none">Aucun filtre</option>
                    
 @if(Auth::check()) <option value="?filter=own">Vos événements</option> @endif
                    <option value="?filter=today">Les événements d'aujourd'hui</option>
                    <option value="?filter=week">Les événements de la semaine</option>
                    <option value="?filter=passed">Les événements passés</option>
                    <option value="?filter=coming">Les événements à venir</option>
                </select>

        </div>

	</form>

	<table class="table table-striped table-hover table-list" >
		<thead>
  			<?php 
  				$orderBy = '';
  				$order = '';
  				if(isset($_GET['orderby']))
  				{
	  				$orderBy = $_GET['orderby'];
	  				$order   = $_GET['order'];
  				}
  			?>  			<tr class="orderBar">
  				<td align="center" width="130"><b>
					<a title="Cliquez pour trier" href="?orderby=created_at&order={{ $orderBy == 'created_at' ? $order == 'desc' ? 'asc' : 'desc' : 'asc' }}">Date de création
					<span class="sort sort1 glyphicon glyphicon-triangle-bottom"></span><span class="sort sort2 glyphicon glyphicon-triangle-top"></span> </a>
				</b></td>
  				<td align="center" width="150"><b>
					<a title="Cliquez pour trier" href="?orderby=date&order={{ $orderBy == 'date' ? $order == 'asc' ? 'desc' : 'asc' : 'desc' }}">Date de l'événement
					<span class="sort sort1 glyphicon glyphicon-triangle-bottom"></span><span class="sort sort2 glyphicon glyphicon-triangle-top"></span> </a>
				</b></td>
				<td align="center" width="180"><b>
					<a title="Cliquez pour trier" href="?orderby=user_id&order={{ $orderBy == 'user_id' ? $order == 'desc' ? 'asc' : 'desc' : 'asc' }}">Auteur
					<span class="sort sort1 glyphicon glyphicon-triangle-bottom"></span><span class="sort sort2 glyphicon glyphicon-triangle-top"></span> </a>
				</b></td>
				<td align="center" width="400"><b>
					<a title="Cliquez pour trier" href="?orderby=name&order={{ $orderBy == 'name' ? $order == 'desc' ? 'asc' : 'desc' : 'asc' }}">Nom de l'événement
					<span class="sort sort1 glyphicon glyphicon-triangle-bottom"></span><span class="sort sort2 glyphicon glyphicon-triangle-top"></span> </a>
				</b></td>
				<td align="center" width="300"><b>
					<a title="Cliquez pour trier" href="?orderby=style&order={{ $orderBy == 'style' ? $order == 'desc' ? 'asc' : 'desc' : 'asc' }}">Style(s) de musique
					<span class="sort sort1 glyphicon glyphicon-triangle-bottom"></span><span class="sort sort2 glyphicon glyphicon-triangle-top"></span> </a>
				</b></td>
				<td align="center" width="120"><b>
					<a title="Cliquez pour trier" href="?orderby=musics&order={{ $orderBy == 'musics' ? $order == 'desc' ? 'asc' : 'desc' : 'asc' }}">Musiques
					<span class="sort sort1 glyphicon glyphicon-triangle-bottom"></span><span class="sort sort2 glyphicon glyphicon-triangle-top"></span> </a>
				</b></td>
			</tr>
		</thead>
		<tbody>
		@forelse($events as $e)
			<tr>
				<td align="center">{{ date_format(date_create_from_format('Y-m-d H:i:s', $e->created_at), 'd/m/Y H:i') }}</td>
				<td align="center">{{ showDate($e->date, 'l j F Y', false) }} </td>
				<td align="center">{{ App\User::where('id', $e['user_id'])->first()['name'] }}</td>
				<td><a href="{{ url('events/'.$e['slug']) }}">{{ $e['name'] }}</a></td>
				<td>{{ strlen($e['style']) > 35 ? substr($e['style'], 0, 35).'...' : $e['style'] }} </td>
				<td align="center">{{ App\VideosOnEvent::where('event_id', $e['id'])->count() }}</td>
			</tr>
		@empty
			<tr>
				<td> - </td>
				<td> - </td>
				<td> - </td>
				<td> - </td>
				<td> - </td>
				<td> - </td>
			</tr>
		@endforelse
		</tbody>
	</table>
@stop