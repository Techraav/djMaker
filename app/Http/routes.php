<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});	

Route::get('about', function(){
	return view('about');
});

Route::get('test', function(){
	$nd = Carbon\Carbon::today()->addMonth();
	$incomingEvents = App\Event::where('private', 0)->where('active', 1)->where('date', '>=', DB::raw('NOW()'))->where('date', '<=', $nd)->orderBy('date', 'ASC')->paginate(5);
	return Response::json($incomingEvents);
});

// AUTH
Route::get('login', 'Auth\AuthController@getLogin')			->name('login')		->middleware('guest');
Route::get('register', 'Auth\AuthController@getRegister')	->name('register')	->middleware('guest');
Route::get('logout', 'Auth\AuthController@logout')			->name('logout')	->middleware('auth');
Route::get('auth/googleplus', 'Auth\AuthController@googlePlus')	->name('googleplus')->middleware('auth');
Route::get('auth/facebook', 'Auth\AuthController@redirectToFacebookProvider')	->middleware('guest');
Route::get('auth/facebook/callback', 'Auth\AuthController@handleFacebookProviderCallback');
Route::get('auth/google', 'Auth\AuthController@redirectToGoogleProvider')		->middleware('auth'); // besoin d'être co avec fb
Route::get('auth/google/callback', 'Auth\AuthController@handleGoogleProviderCallback');
Route::post('login', 'Auth\AuthController@Login')		->name('login');
Route::post('register', 'Auth\AuthController@Register')	->name('register');
// Route::post('auth/link', 'Auth\AuthController@linkAccounts')	->name('register');
// Route::post('auth/linkfromfacebook', 'Auth\AuthController@linkToBasicAccount')	->name('register');



Route::group(['prefix' => '/', 'middleware' => 'banned'], function()
{
	// EVENT ROUTES
	Route::group(['prefix' => 'events'], function()
	{
		Route::get('/', 'EventController@index')				->name('events.index');
		Route::get('past', 'EventController@past')				->name('events.index');
		Route::get('show/{slug}', 'EventController@show')		->name('events.show');
		Route::get('create', 'EventController@create')			->name('events.create')		->middleware(['auth', 'google']);
		Route::get('preview', 'EventController@preview')		->name('events.preview');
		Route::post('delete', 'EventController@destroy')		->name('events.delete')		->middleware('auth');

	});

	Route::group(['prefix' => 'playlists'], function()
	{
		Route::get( '{playlist_id}', 'PlaylistController@showJs' )->name( 'playlists.show' );
		Route::post( 'likeordislike', 'PlaylistController@likeOrDislike' )->name( 'playlists.likeordislike' );
		Route::post( 'validation', 'PlaylistController@validation' )->name( 'playlists.validation' );
		Route::post( 'addmusic', 'PlaylistController@addMusic' )->name( 'playlists.addmusic' );
		Route::post( 'addplaylist', 'PlaylistController@addPlaylist' )->name( 'playlists.addplaylist' );
		Route::get( 'export/{playlist_id}', 'PlaylistController@exportForm' )->name( 'playlists.exportform' )	->middleware(['auth', 'google']);
		Route::get( 'export/{playlist_id}/{orderBy}/{order}', 'PlaylistController@exportForm' )->name( 'playlists.exportform' )	->middleware(['auth', 'google']);
		Route::post( 'export', 'PlaylistController@export' )->name( 'playlists.export' )	->middleware(['auth', 'google']);
		Route::post( 'delete', 'PlaylistController@destroy' )->name( 'playlists.destroy' )	->middleware('auth');

		Route::get( 'show/{id}', 'PlaylistController@show' )->name( 'playlists.show' );
		Route::get( 'show/{id}/{orderBy}/{order}', 'PlaylistController@show' )->name( 'playlists.show' );
		Route::get( 'show/filter/{id}/validation/{filter}', 'PlaylistController@filtered' )->name( 'playlists.showfitlered' );
		Route::get( 'show/filter/{id}/validation/{filter}/{orderBy}/{order}', 'PlaylistController@filtered' )->name( 'playlists.showfiltered' );
		Route::get( 'ids/{id}', 'PlaylistController@ids')->name('playlists.ids');
		Route::get( '{id}/videos', 'PlaylistController@videos')->name('playlists.videos');
		Route::post( 'searchmusic', 'PlaylistController@searchForVideos' )->name('playlists.searchForVideos');
	});

	Route::group([ 'prefix' => 'videos'], function(){
		Route::get('{id}', 'VideoController@getVideo')->name('videos.getvideo');
	});
});