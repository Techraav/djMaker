<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

	protected $table = 'events';
	public $timestamps = true;
	protected $dates = ['date'];
	protected $fillable = array('name', 'user_id', 'slug', 'description', 'private', 'date', 'start', 'end', 'active', 'city', 'adress');

	static $maxPlaylists = 5;

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function comments()
	{
		return $this->hasMany('App\Comment');
	}

	public function playlists()
	{
		return $this->belongsToMany('App\Playlist')->withTimestamps();
	}

}