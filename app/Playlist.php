<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model {

	protected $table = 'playlists';
	public $timestamps = true;
	protected $fillable = array('event_id', 'description', 'active', 'name');


	public function event()
	{
		return $this->belongsToMany('App\Event')->withTimestamps();
	}

	public function styles()
	{
		return $this->belongsToMany('App\Style');
	}

	public function videos()
	{
		return $this->belongsToMany('App\Video')->withPivot(['validation'])->withTimestamps();
	}

	public function playlistVideos()
	{
		return $this->hasMany('App\PlaylistVideo')->with('video');
	}

	public function nbOfPlaylistVideos()
	{
		return $this->hasMany('App\PlaylistVideo');
	}

}