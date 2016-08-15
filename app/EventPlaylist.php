<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventPlaylist extends Model {

	protected $table = 'event_playlist';
	public $timestamps = true;
	protected $fillable = array('playlist_id', 'event_id', 'created_at', 'updated_at');

}