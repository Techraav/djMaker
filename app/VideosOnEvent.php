<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideosOnEvent extends Model {

	protected $table = 'videos_on_events';
	public $timestamps = true;
	protected $fillable = array('video_id', 'event_id', 'timestamps', 'user_id', 'validation', 'mark', 'artist', 'title');

	public function LikesOrDislikes()
	{
		return $this->hasMany('UsersLikeDislikeVideo');
	}

}