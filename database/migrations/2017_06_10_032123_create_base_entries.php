<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\User;
use App\Event;
use App\Comment;
use App\Article;
use App\Likes;
use App\News;
use App\Playlist;
use App\Style;
use App\Video;

class CreateBaseEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $u = User::create([
            'first_name'  => 'test',
            'last_name'  => 'test',
            'email' => 'test@test.fr',
            'level' => 1,
            ]);

        $u2 = User::create([
            'first_name'  => 'test2',
            'last_name'  => 'test2',
            'email' => 'test2@test.fr',
            'level' => 1,
            ]);

        $u3 = User::create([
            'first_name'  => 'test3',
            'last_name'  => 'test3',
            'email' => 'test3@test.fr',
            'level' => 2,
            ]);

        $e1 = Event::create([
            'name'  => 'test event',
            'user_id'   => 3,
            'slug'  => 'test-event-1',
            'date'  => '2016-10-17',
            'start' => '18:00:00',
            'end'   => '5:00:00',
            ]);

        $e2 = Event::create([
            'name'  => 'test event',
            'user_id'   => 1,
            'slug'  => 'test-event-2',
            'date'  => '2016-08-22',
            'start' => '19:00:00',
            'end'   => '5:00:00',
            ]);

        Event::create([
            'name'  => 'test event',
            'user_id'   => 2,
            'slug'  => 'test-event-3',
            'date'  => '2017-01-07',
            'start' => '18:00:00',
            'end'   => '5:00:00',
            ]);

        $p1 = Playlist::create([
            'name' => 'Playlist principale !',
            ]);

        $p2 = Playlist::create([
            'name' => 'The Playlist !',
            ]);

        $p3 = Playlist::create([
            'name' => 'Playlist secondaire',
            ]);

        Playlist::create([
            'name' => 'The Playlist !',
            ]);

        $p1->styles()->sync([1, 2, 3]);
        $p2->styles()->sync([1, 4]);
        $p3->styles()->sync([1, 3, 5, 7]);

        $e1->playlists()->sync([1, 3]);
        $e2->playlists()->sync([2]);

        Comment::create([
            'event_id'  => 2,
            'user_id'   => 2,
            'content'   => 'Sooo goooood',
            ]);

        Comment::create([
            'event_id'  => 3,
            'user_id'   => 2,
            'content'   => 'Sooo goooood :D',
            ]);

        Comment::create([
            'event_id'  => 2,
            'user_id'   => 3,
            'content'   => 'Sooo goooood !!!',
            ]);

        Video::create([
            'url'   => '7l48bfQuJeE',
            'artist'    => 'Chill Bump',
            'name'  => 'Lost In The Sound',
            'tags'  => 'chill bump lost in the sound'
            ]);

        Video::create([
            'url'   => 'XxdPJvhQaMU',
            'artist'    => 'Chill Bump',
            'name'  => 'Water boycotter',
            'tags'  => 'chill bump water boycotter'
            ]);

        Video::create([
            'url'   => 'kWXAYDQ_K7k',
            'artist'    => 'Chill Bump',
            'name'  => 'The Memo',
            'tags'  => 'chill bump the memo'
            ]);

        $pivot1 = $p1->videos()->sync([1, 3]);
        $pivot2 = $p2->videos()->sync([2]);
        $pivot3 = $p3->videos()->sync([1, 2, 3]);

        News::create([
            'title' => 'news test',
            'content'   => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ',
            'user_id'   => 2,
            'slug'  => 'text-news-1',
            ]);

        News::create([
            'title' => 'news test 2',
            'content'   => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ',
            'user_id'   => 3,
            'slug'  => 'text-news-2',
            ]);

        Article::create([
            'title' => 'Article test 1',
            'content'   => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ',
            'user_id'   => 2,
            'slug'  => 'text-article-1',
            ]);

        Article::create([
            'title' => 'Article test 2',
            'content'   => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ',
            'user_id'   => 3,
            'slug'  => 'text-article-2',
            'event_id'  => 2
            ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
