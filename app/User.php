<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use \Google_Client;
use \Google_Service_Youtube;
use \Google_Service_YouTube_PlaylistSnippet;
use \Google_Service_YouTube_PlaylistStatus;
use \Google_Service_YouTube_Playlist;
use \Google_Service_YouTube_ResourceId;
use \Google_Service_YouTube_PlaylistItemSnippet;
use \Google_Service_YouTube_PlaylistItem;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

	protected $table = 'users';
	public $timestamps = true;
	protected $fillable = array('first_name', 'last_name', 'email', 'facebook_id', 'avatar', 'level', 'password', 'bannned', 'slug', 'google_token', 'google_email', 'google_id');

	public function comments()
	{
		return $this->hasMany('App\Comment');
	}

	public function events()
	{
		return $this->hasMany('App\Event');
	}

	public function videoLiked()
	{
		return $this->belongsToMany('App\PlaylistVideo', 'likes', 'user_id', 'video_id')->withPivot(['value'])->withTimestamps();
	}

	public function playlistVideos()
	{
		return $this->hasMany('App\PlaylistVideo');
	}

	public function news()
	{
		return $this->hasMany('App\News');
	}

	public function articles()
	{
		return $this->hasMany('App\Article');
	}

	public function likes($vid_id, $value)
	{
		$this->videoLiked()->detach($vid_id);
		return $this->videoLiked()->attach([$vid_id => ['value' => $value]]);
	}

	public function getYoutubeClient()
	{
		if($this->google_token != '')
		{
			$client = new Google_Client();
			$client->setClientId(env('GOOGLE_CLIENT_ID'));
			$client->setClientSecret(env('GOOGLE_SECRET'));
			$client->setScopes('https://www.googleapis.com/auth/youtube');
			$client->addScope('https://www.googleapis.com/auth/plus.login');
			$client->setRedirectUri(url('auth/google/callback'));
			// $client->authenticate('4/KGM2xps8lH6gAv2NWbhbqpZv9-Hwjg-M0OZQ22-l_Wg');
			$client->setAccessToken($this->google_token);
			if($client->getAccessToken())
			{
				$client->setExpiresIn('7200');
				$youtube = new Google_Service_Youtube($client);
				return $youtube;
			}
		}
		return false;
	}

	/**
	*	Create a Youtube Playlist and add some videos
	*
	*	@var $data : [title, description, privacy]
	*	@var $video_or_videos : video(s) to add to the playlist
	*/
	public function createPlaylist(array $data, $video_or_videos)
	{
		$youtube = $this->getYoutubeClient();
		if($youtube != false)
		{
			try{
				$pSnippet = new Google_Service_YouTube_PlaylistSnippet();
				$pSnippet->setTitle($data['title']);
				$pSnippet->setDescription($data['description']);
		 
		 		$pStatus = new Google_Service_YouTube_PlaylistStatus();
				$pStatus->setPrivacyStatus($data['privacy']);

				$playlist = new Google_Service_YouTube_Playlist();
			    $playlist->setSnippet($pSnippet);
			    $playlist->setStatus($pStatus);

				$playlistResponse = $youtube->playlists->insert('snippet,status', $playlist, array());
				dd();
				$playlistId = $playlistResponse['id'];
				
				addToPlaylist($playlistId, $video_or_videos);

				return true;
			 }
			 catch (Google_Service_Exception $e) 
			 {
			  	return false;
			 }
			 catch (Google_Exception $e) 
			 {
			  	return false;
			 }
		}

		return false;
	}

	public function addToPlaylist($playlistId, $video_or_videos)
	{
		if(is_array($video_or_videos))
		{
			$videos = $video_or_videos;

			foreach ($videos as $v) 
			{
				$resourceId = new Google_Service_YouTube_ResourceId();
			    $resourceId->setVideoId($v->url);
			    $resourceId->setKind('youtube#video');

			    $playlistItemSnippet = new Google_Service_YouTube_PlaylistItemSnippet();
			    $playlistItemSnippet->setPlaylistId($playlistId);
			    $playlistItemSnippet->setResourceId($resourceId);

			    $playlistItem = new Google_Service_YouTube_PlaylistItem();
			    $playlistItem->setSnippet($playlistItemSnippet);
			    $playlistItemResponse = $youtube->playlistItems->insert('snippet,contentDetails', $playlistItem, array());
			}
		}else
		{
			$video = $videos_or_videos;

			$resourceId = new Google_Service_YouTube_ResourceId();
		    $resourceId->setVideoId($video->url);
		    $resourceId->setKind('youtube#video');

		    $playlistItemSnippet = new Google_Service_YouTube_PlaylistItemSnippet();
		    $playlistItemSnippet->setPlaylistId($playlistId);
		    $playlistItemSnippet->setResourceId($resourceId);

		    $playlistItem = new Google_Service_YouTube_PlaylistItem();
		    $playlistItem->setSnippet($playlistItemSnippet);
		    $playlistItemResponse = $youtube->playlistItems->insert('snippet,contentDetails', $playlistItem, array());
		}
	}

}