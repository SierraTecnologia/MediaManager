<?php

use Illuminate\Database\Seeder;

use MediaManager\Models\Video;
use MediaManager\Models\Playlist;

class PlaylistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            
        $qnt = rand(3, 8);
        factory(Playlist::class, $qnt)->create()->each(function ($playlist) {
            $videos = rand(0, 5);
            for ($i = 0; $i<$videos; ++$i) {
                $playlist->videos()->save(factory(Video::class)->make());
            }

            // $sorte = rand(0, 100);
            // if ($sorte>10) {
            //     $playlist->orders()->save(factory(Playlist::class)->make());
            // }
        });

    }
}