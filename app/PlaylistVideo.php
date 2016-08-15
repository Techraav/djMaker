<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlaylistVideo extends Model {

	protected $table = 'playlist_video';
	public $timestamps = true;
		protected $fillable = array('video_id', 'playlist_id', 'validation', 'created_at', 'updated_at', 'user_id');

	public function usersWhoLiked()
	{
		return $this->belongsToMany('App\User', 'likes', 'video_id', 'user_id')->withTimestamps();
	}

	public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}

	public function video()
	{
		return $this->belongsTo('App\Video');
	}

	public function playlist()
	{
		return $this->belongsTo('App\Playlist');
	}

	public function numberOfLikes()
	{
		return $this->belongsToMany('App\User', 'likes', 'video_id', 'user_id');
	}

	public function likes()
	{
		return $this->hasMany('App\Likes', 'video_id');
	}

}