<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

	protected $table = 'comments';
	public $timestamps = true;
	protected $fillable = array('content', 'user_id', 'event_id');

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function event()
	{
		return $this->belongsTo('App\Event');
	}

}