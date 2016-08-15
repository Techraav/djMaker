<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Likes extends Model {

	protected $table = 'likes';
	public $timestamps = true;
	protected $fillable = array('user_id', 'video_id', 'value', 'created_at', 'updated_at');

}