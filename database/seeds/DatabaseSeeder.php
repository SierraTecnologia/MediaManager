<?php

use MediaManager\Models\Collaborator;

use MediaManager\Models\Computer;
use MediaManager\Models\Group;
use MediaManager\Models\Playlist;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        $this->call([
            UsersTableSeeder::class,
        ]);
        if (env('APP_ENV')!=='production') {
            $this->call([
                PlaylistsTableSeeder::class,
                GroupsTableSeeder::class,
                // MidiaPhotoSeeder::class,
            ]);
        }

        // if (env('APP_ENV')!=='production') {
        //     // factory(MediaManager\Models\User::class, 1)->create();




            
        //     $qnt = rand(100, 1000);
        //     factory(Group::class, $qnt)->create()->each(function ($group) {
        //         $contacts = rand(0, 15);
        //         for ($i = 0; $i<$contacts; ++$i) {
        //             $group->contacts()->save(factory(Computer::class)->make());
        //         }

        //         $sorte = rand(0, 100);
        //         if ($sorte>10) {
        //             $group->orders()->save(factory(Playlist::class)->make());
        //         }
        //     });

        //     // factory(MediaManager\Models\Playlist::class, 10)->create()->each(function () {
        //     //     factory(MediaManager\Models\Group::class, 10)->create();
        //     // });
        // }
        
    }
}
