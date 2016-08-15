<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Playlist;
use Validator;
use App\PlaylistVideo;
use App\EventPlaylist;
use App\Likes;
use App\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;
use Alaouy\Youtube\Facades\Youtube;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $playlist = Playlist::find($request->playlist_id);
        if($playlist->delete())
        {
            Flash::success('Votre playlist a été supprimée avec succès !');
            return Redirect::back();
        }
        Flash::error('Une erreur est survenue, impossible de supprimer la playlist.');
        return Redirect::back();
    }

    public function filtered($id, $filter, $orderBy='name', $order='asc')
    {
        return $this->show($id, $orderBy, $order, $filter);
    }

    /**
     * show the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @param $filter : validation : -1/0/1
     */
    public function show($id, $orderBy='name', $order='asc', $filter='none')
    {
        if(($order != 'asc' && $order != 'desc') || ($orderBy != 'name' && $orderBy != 'score' && $orderBy != 'validation')){
            $orderBy = 'name';
            $order = 'asc';
            Flash::error('Une erreur est survenue, impossible de trier les musiques.');
        }

        $originalOrder = $order;

        if($filter != 'none' && $filter != 0 && $filter != 1 && $filter != -1)
        {
            $filter = 'none';
            Flash::error('Une erreur est survenue, impossible de filtrer les musiques.');
        }

        $orderBy = $orderBy == 'name' ? 'videos.artist' : $orderBy;
        // $orderParam = $order;
        $playlist = Playlist::with('nbOfPlaylistVideos')->find($id);
        $videos = PlaylistVideo::where('playlist_id', $playlist->id)
                            ->with('likes', 'video');

        if($filter != 'none')
        {
            $videos = $videos->where('validation', $filter);
        }

        if($orderBy == 'score')
        {
            $order = $order == 'asc' ? 'desc' : 'asc';
        }
        else
        {
            $videos = $videos->select('playlist_video.*');
            $videos = $videos->join('videos', 'playlist_video.video_id', '=', 'videos.id');
        }

        $videos = $videos->orderBy($orderBy, $order)->paginate(50);
        // $order = $orderParam;

        $inv = ['asc' => 'desc', 'desc' => 'asc'];

        $startUrl = 'playlists/show';

        if($filter != 'none')
        {
            $urlArtist = $startUrl . '/filter/'.$id.'/validation/'.$filter.'/name/desc';
            $urlScore = $startUrl . '/filter/'.$id.'/validation/'.$filter.'/score/desc';
            $urlValidation = $startUrl . '/filter/'.$id.'/validation/'.$filter.'/validation/desc';
        }else
        {
            $urlArtist = $startUrl .'/'. $id . '/name/desc';
            $urlScore = $startUrl .'/'. $id . '/score/desc';
            $urlValidation = $startUrl .'/'. $id . '/validation/desc';
        }

        if($orderBy == 'videos.artist' && $order == 'desc')
        {
            if($filter != 'none')
            {
                $urlArtist = 'playlists/show/filter/'.$id.'/validation/'.$filter;
            }
            else
            {
                $urlArtist = 'playlists/show/'.$id;
            }
        }
        elseif($orderBy == 'score')
        {
            if($filter != 'none')
            {
                $urlScore = 'playlists/show/filter/'.$id.'/validation/'.$filter.'/score/'.$order;
            }
            else
            {
                $urlScore = 'playlists/show/'.$id.'/score/'.$order;
            }
        }
        elseif($orderBy == 'validation')
        {
            if($filter != 'none')
            {
                $urlValidation = 'playlists/show/filter/'.$id.'/validation/'.$filter.'/validation/'.$inv[$order];
            }
            else
            {
                $urlValidation = 'playlists/show/'.$id.'/validation/'.$inv[$order];
            }
        }

        $top = false;
        if($originalOrder == 'desc')
        {
            $top = $orderBy;
            $top = $top == 'videos.artist' ? 'name' : $top;
        }

        
        return view('playlists.show', compact('playlist', 'videos', 'top', 'urlArtist', 'urlScore', 'urlValidation'));
    }

    public function showJs($playlist_id)
    {
        $playlist = Playlist::with(['event' => function($query){ $query->with('user'); }, 'styles', 'videos' => function($query){ $query->count(); },])->find($playlist_id);

        $videos = PlaylistVideo::where('playlist_id', $playlist->id)
                                ->with(['user' => function($query) { $query->get(['first_name', 'last_name']);}, 'usersWhoLiked' => function($query) { $query->get(['user_id', 'value']); }, 'video'])
                                ->select('playlist_video.*')
                                ->join('videos', 'playlist_video.video_id', '=', 'videos.id')
                                ->orderBy('videos.artist', 'asc')
                                ->paginate(15);


        $user_id = Auth::check() ? Auth::user()->id : 'null';

        $array = ['playlists' => $playlist->toArray(), 'videos' => $videos->toArray(), 'user_id' => $user_id];

        return response()->json($array);
    }

    public function likeOrDislike(Request $request)
    {
        if(Auth::check())
        {
            $playlist_video_id = $request->playlist_video_id;
            $playlistVideo = PlaylistVideo::find($playlist_video_id);
            $value = $request->value;

            $user = Auth::user();

            $line = Likes::where('user_id', $user->id)->where('video_id', $playlist_video_id)->first();
            $bddValue = empty($line) ? false : $line->value;

            if($value != $bddValue)
            {
                $query = $user->likes($playlist_video_id, $value);
                if($bddValue == 1)
                {
                    $playlistVideo->score += -2;
                }elseif($bddValue == -1)
                {
                    $playlistVideo->score += 2;
                }else
                {
                    $playlistVideo->score += $value;
                }
            }else
            {
                $query = $user->likes($playlist_video_id, 0);
                if($bddValue == 1)
                {
                    $playlistVideo->score += -1;
                }elseif($bddValue == -1)
                {
                    $playlistVideo->score += 1;
                }else
                {
                    $playlistVideo->score += -$value;
                }
            }

            $playlistVideo->save();

            $playlistVideo = PlaylistVideo::with(['numberOfLikes' => function($query){ $query->get(['value']); }])->find($playlist_video_id);

            return response()->json($playlistVideo);
        }
    }

    public function validation(Request $request)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $playlist_video_id = $request->playlist_video_id;
            $playlist_video = PlaylistVideo::with(['playlist' => function($query){ $query->with('event'); }])->find($playlist_video_id);
            $event = $playlist_video->playlist->event[0];
            if($user->id == $event->user_id)
            {
                $value = $request->value;
                if($playlist_video->validation == $value)
                {
                    $playlist_video->validation = 0;
                }else
                {
                    $playlist_video->validation = $request->value;
                }

                $playlist_video->save();

                return response()->json();
            }
        }
    }

    public function addMusic(Request $request)
    {

        $artist = $request->artist;
        $name = $request->name;
        $playlist_id = $request->playlist_id;
        $link = $request->link;

        $playlistVideo = false;

        $video_id = Youtube::parseVidFromUrl($link);

        // Si l'utilisateur est connecté, sinon on utilise l'utilisateur null null id=1
        $user_id = Auth::check() ? Auth::user()->id : 1;

        $vid = Video::where('url', $video_id)->first();
        $vid2 = Video::where('artist', 'LIKE', $artist)->where('name', 'LIKE', $name)->first();

        
        $check = !empty($vid) || !empty($vid2);
        if($check)
        {
            if(!empty($vid))
            {
                $pv = PlaylistVideo::where('video_id', $vid->id)->first();

            }
            if(empty($pv))
            {
                if(!empty($vid))
                {
                    $playlistVideo = PlaylistVideo::create([
                        'video_id' => $vid->id,
                        'playlist_id' => $playlist_id,
                        'user_id' => $user_id,
                        ]);
                }elseif(!empty($vid2))
                {
                    $playlistVideo = PlaylistVideo::create([
                        'video_id' => $vid2->id,
                        'playlist_id' => $playlist_id,
                        'user_id' => $user_id,
                        ]);
                }
            }
        }else
        {
            $video = Video::create([
                'url' => $video_id,
                'name' => $name,
                'artist' => $artist,
                'tags'  => strtolower($name).' '.strtolower($artist),
                ]);

            $playlistVideo = PlaylistVideo::create([
                'video_id' => $video->id,
                'playlist_id' => $playlist_id,
                'user_id' => $user_id,
                ]);
        }

        if($playlistVideo != false){
            $playlistVideo->load('video');
        }

        return response()->json($playlistVideo);
    }

    public function addPlaylist(Request $request)
    {
        $validation = ['event_id' => 'required'];
        $validator = Validator::make($request->all(), $validation);

        if($validator->fails())
        {
            Flash::error('Impossible de créer votre playlist, veuillez réessayer.');
            return Redirect::back();
        }

        $event_id = $request->event_id;
        $styles = $request->styles; // Array
        $event_playlists = EventPlaylist::where('event_id', $event_id)->get();
        $name = $request->name != '' ? $request->name : 'Playlist '.($event_playlists->count()+1);

        if($event_playlists->count() >= 3)
        {
            Flash::error('Vous ne pouvez pas créer plus de 3 playlists par événement.');
            return Redirect::back();
        }

        $playlist = Playlist::create(['name' => $name]);

        $playlist->event()->sync([$event_id]);

        if(count($styles) > 0)
        {
            $playlist->styles()->sync($styles);
        }

        return Redirect::back();
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $playlist = Playlist::with(['playlistVideos' => function($query){ $query->where('validation', '1') ;}])->find($request->playlist_id);
        $videos = $playlist->videos;

        $title = $request->title == '' ? $playlist->name : $request->title;
        $title .= ' - DjMaker ' . date('d/m/Y H:i:s');

        $addToDescription = 'Playlist créée avec DjMaker.'; 
        $description = $request->description = '' ? $addToDescription : $request->description .'\n <br /> '.$addToDescription;

        $privacy = isset($request->privacy) ? 1 : 0;

        $array = ['title' => $title, 'description' => $description, 'privacy' => $privacy];

        $user->createPlaylist($array, $playlist->videos);
    }

    public function exportForm($playlist_id, $orderBy='videos.artist', $order='asc')
    {
        $playlist = Playlist::find($playlist_id);
        $videos = PlaylistVideo::where('playlist_id', $playlist->id)
                            ->with('likes', 'video')
                            ->where('validation', '1');

        if($orderBy == 'score')
        {
            $order = $order == 'asc' ? 'desc' : 'asc';
        }
        else
        {
            $videos = $videos->select('playlist_video.*');
            $videos = $videos->join('videos', 'playlist_video.video_id', '=', 'videos.id');
        }

        $videos = $videos->orderBy($orderBy, $order)->paginate(25);
        
        return view('playlists.export', compact('playlist', 'videos'));
    }

    public function ids($id, $validation=false)
    {
        $ids = PlaylistVideo::where('playlist_id', $id);

        if($validation)
        {
            $ids = $ids->where('validation', $validation);
        }

        $ids = $ids->get(['video_id']);

        return response()->json($ids);
    }

    public function videos($id) 
    {
        $pv = PlaylistVideo::with('video')->where('playlist_id', $id)->get();
        $videos = [];

        for ($i=0; $i<$pv->count(); $i++) { 
            $videos[$i]['artist'] = $pv[$i]->video->artist;
            $videos[$i]['song'] = $pv[$i]->video->name;
            $videos[$i]['link'] = $pv[$i]->video->url;
        }

        return response()->json($videos);
    }

    public function searchForVideos(Request $request)   
    {
        $response = PlaylistVideo::where('playlist_id', $request->playlist_id)
                                ->with('video', 'likes')
                                ->select('playlist_video.*')
                                ->join('videos', 'playlist_video.video_id', '=', 'videos.id')
                                ->where('videos.tags', 'LIKE', '%'.strtolower(trim($request->search)).'%')
                                ->limit(10)
                                ->get();

        $user_id = Auth::check() ? Auth::user()->id : 'null';

        return response()->json([$response, $user_id]);
    }
}
