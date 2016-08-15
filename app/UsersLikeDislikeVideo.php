<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersLikeDislikeVideo extends Model {

	protected $table = 'users_like_dislike_video';
	public $timestamps = false;
	protected $fillable = array('user_id', 'video_on_event_id', 'value');

	public function video()
	{
		return $this->belongsTo('VideosOnEvent', 'video_on_event_id');
	}

}