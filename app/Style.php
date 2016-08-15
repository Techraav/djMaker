<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Style extends Model {

	protected $table = 'styles';
	public $timestamps = false;
	protected $fillable = array('name');

	public function playlists()
	{
		return $this->belongsToMany('App\Playlist');
	}

}