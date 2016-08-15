<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlaylistStyle extends Model {

	protected $table = 'playlist_style';
	public $timestamps = false;
	protected $fillable = array('playlist_id', 'style_id', 'created_at', 'updated_at');

}