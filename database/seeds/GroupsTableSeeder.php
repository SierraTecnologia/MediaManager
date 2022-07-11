<?php

use Illuminate\Database\Seeder;

use MediaManager\Models\Playlist;
use MediaManager\Models\Computer;
use MediaManager\Models\Group;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            
        $qnt = rand(1, 4);
        factory(Group::class, $qnt)->create()->each(function ($group) {
            // $computers = rand(0, 15);
            // for ($i = 0; $i<$computers; ++$i) {
            //     $group->computers()->save(factory(Computer::class)->make());
            // }

            // $sorte = rand(0, 100);
            // if ($sorte>10) {
            //     $group->orders()->save(factory(Playlist::class)->make());
            // }
        });

    }
}
