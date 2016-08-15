<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {

	protected $table = 'articles';
	public $timestamps = true;
	protected $fillable = array('user_id', 'event_id', 'title', 'content', 'slug', 'active');

	public function user()
	{
		return $this->belongsTo('App\User');
	}

}