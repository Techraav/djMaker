<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Event;
use App\Style;

class CreateRequiredEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        User::create([
            'first_name' => 'null',
            'last_name' => 'null',
            'email' => 'null@null',
            ]);

        Event::create([
            'name' => 'null',
            'user_id' => 1,
            'slug' => 'null',
            'private'   => 1,
            'active'    => 0,
            ]);

        $styles = ['Rock', 'Hard Rock', 'Métal', 'Techno', 'House', 'Futur House', 'Rap', 'R&B', 'Pop', 'Pop Rock', 'Jazz', 'Blues'];
        $this->createStyles($styles);
    }

    protected function createStyles(array $styles)
    {
        foreach ($styles as $s) {
            Style::create([ 'name' => $s ]);
        }
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
