<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laracasts\Flash\Flash;

use App\User;
use App\Playlist;
use App\Comments;
use App\Event;
use App\PlaylistVideo;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::where('date', '>=', DB::raw('NOW()'))
                ->where('private', 0)
                ->where('active', 1)
                ->orderBy('date', 'ASC')
                ->with(['comments' => function($query){ $query->count(); },
                        'user', 
                        'playlists' => function($query){ $query->with('styles'); }
                    ])
                ->paginate(9);
        $yourEvents = '';
        if(Auth::check())
        {
            $yourEvents = Event::where('user_id', Auth::user()->id)->orderBy('date', 'ASC')->get();
        }

        return view('events.index', compact('events', 'yourEvents'));
    }

    public function past()
    {
        $events = Event::where('date', '<', DB::raw('NOW()'))
                ->where('private', 0)
                ->where('active', 1)
                ->orderBy('date', 'DESC')
                ->with(['comments' => function($query){ $query->count(); },
                        'user', 
                        'playlists' => function($query){ $query->count(); }   
                    ])
                ->paginate(10);

        return view('events.past', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $event = Event::where('slug', $slug)->first();

        if(Auth::check()){
            if(($event->private == 1 || $event->active == 0) && $event->user_id != Auth::user()->id ){
                $event = '';
            }
        }

        if(empty($event) || $event == ''){
            Flash::error('Cet événement n\'existe pas.');
            return redirect('events');
        }

        $event->load(['user', 'comments']);

        return view('events.show', compact('event', 'playlists'));
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
        if(Event::find($request->id)->delete())
        {
            Flash::success('Votre événement a été supprimé avec succès !');
            return redirect('events');
        }else
        {
            Flash::error('Une erreur est survenue, impossible de supprimer l\'événement. Veuillez réessayer');
            return Redirect::back();
        }
    }

    public function preview()
    {
        return view('events.preview');
    }

}
