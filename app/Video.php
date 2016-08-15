<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model {

	protected $table = 'videos';
	public $timestamps = true;
	protected $fillable = array('url', 'artist', 'name', 'tags');

	public function playlists()
	{
		return $this->belongsToMany('App\Playlist')->withPivot(['validation'])->withTimestamps();
	}	

	public function playlistVideos()
	{
		return $this->hasMany('App\PlaylistVideo');
	}
}