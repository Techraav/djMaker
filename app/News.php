<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model {

	protected $table = 'news';
	public $timestamps = true;
	protected $dates = ['published_at'];
	protected $fillable = array('title', 'content', 'user_id', 'published_at', 'active', 'slug');

	public function user()
	{
		return $this->belongsTo('App\User');
	}

}