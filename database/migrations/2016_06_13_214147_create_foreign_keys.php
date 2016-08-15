<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('events', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('playlist_style', function(Blueprint $table) {
			$table->foreign('playlist_id')->references('id')->on('playlists')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('playlist_style', function(Blueprint $table) {
			$table->foreign('style_id')->references('id')->on('styles')
						->onDelete('restrict')
						->onUpdate('cascade');
		});
		Schema::table('playlist_video', function(Blueprint $table) {
			$table->foreign('video_id')->references('id')->on('videos')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('playlist_video', function(Blueprint $table) {
			$table->foreign('playlist_id')->references('id')->on('playlists')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('playlist_video', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('comments', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('comments', function(Blueprint $table) {
			$table->foreign('event_id')->references('id')->on('events')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('likes', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('likes', function(Blueprint $table) {
			$table->foreign('video_id')->references('id')->on('playlist_video')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('news', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('articles', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('articles', function(Blueprint $table) {
			$table->foreign('event_id')->references('id')->on('articles')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('event_playlist', function(Blueprint $table) {
			$table->foreign('playlist_id')->references('id')->on('playlists')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('event_playlist', function(Blueprint $table) {
			$table->foreign('event_id')->references('id')->on('events')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('events', function(Blueprint $table) {
			$table->dropForeign('events_user_id_foreign');
		});
		Schema::table('playlist_style', function(Blueprint $table) {
			$table->dropForeign('playlist_style_playlist_id_foreign');
		});
		Schema::table('playlist_style', function(Blueprint $table) {
			$table->dropForeign('playlist_style_style_id_foreign');
		});
		Schema::table('playlist_video', function(Blueprint $table) {
			$table->dropForeign('playlist_video_video_id_foreign');
		});
		Schema::table('playlist_video', function(Blueprint $table) {
			$table->dropForeign('playlist_video_playlist_id_foreign');
		});
		Schema::table('playlist_video', function(Blueprint $table) {
			$table->dropForeign('playlist_video_user_id_foreign');
		});
		Schema::table('comments', function(Blueprint $table) {
			$table->dropForeign('comments_user_id_foreign');
		});
		Schema::table('comments', function(Blueprint $table) {
			$table->dropForeign('comments_event_id_foreign');
		});
		Schema::table('likes', function(Blueprint $table) {
			$table->dropForeign('likes_user_id_foreign');
		});
		Schema::table('likes', function(Blueprint $table) {
			$table->dropForeign('likes_video_id_foreign');
		});
		Schema::table('news', function(Blueprint $table) {
			$table->dropForeign('news_user_id_foreign');
		});
		Schema::table('articles', function(Blueprint $table) {
			$table->dropForeign('articles_user_id_foreign');
		});
		Schema::table('articles', function(Blueprint $table) {
			$table->dropForeign('articles_event_id_foreign');
		});
		Schema::table('event_playlist', function(Blueprint $table) {
			$table->dropForeign('event_playlist_playlist_id_foreign');
		});
		Schema::table('event_playlist', function(Blueprint $table) {
			$table->dropForeign('event_playlist_event_id_foreign');
		});
	}
}