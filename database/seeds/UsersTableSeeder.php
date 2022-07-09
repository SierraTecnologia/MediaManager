<?php

use App\Models\Collaborator;

use App\Models\Role;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $fakerBr = \Faker\Factory::create('pt_BR');
        // User::truncate();
        // Role::truncate();

        $root = Role::create(['name' => 'root']);
        // $root->givePermissionTo(Permission::all()); // @todo, deu erro aqui

        $admin = Role::create(['name' => 'admin']);
        // $admin->givePermissionTo(Permission::all()); // @todo, deu erro aqui


        // Marcos
        $user = User::firstOrCreate([
            'name'              => 'Marcos',
            'phone'               => '2198330-9228',
            'email'             => 'suporte@mesh.net.br',
            'password'          => 'q1w2e3r4', //bcrypt('q1w2e3r4'),
            'role_id'           => Role::$ADMIN
        ]);

        // Marcos
        $user = User::firstOrCreate([
            'name'              => 'Renato',
            'email'             => 'endoterabr@gmail.com',
            'password'          => 'q1w2e3r4', //bcrypt('q1w2e3r4'),
            'role_id'           => Role::$ADMIN
        ]);

        
        User::firstOrCreate([
            'name'              => 'Ricardo Sierra',
            'phone'             => '21999193898',
            'email'             => 'ricardo@ricasolucoes.com.br',
            'password'          => 'q1w2e3r4', //bcrypt('q1w2e3r4'),
            'role_id'           => Role::$GOOD
        ]);
        User::firstOrCreate([
            'name'              => 'Dimas',
            'phone'             => '13281810201',
            'email'             => 'dimas@ricasolucoes.com.br',
            'password'          => 'q1w2e3r4', //bcrypt('q1w2e3r4'),
            'role_id'           => Role::$GOOD
        ]);

        // Sitec Colaboradores
        User::firstOrCreate([
            'name'              => 'Aline do Vale',
            'phone'               => '17161603757',
            'email'             => 'aline@ricasolucoes.com.br',
            'password'          => 'q1w2e3r4', //bcrypt('q1w2e3r4'),
            'role_id'           => Role::$GOOD
        ]);
    }
}
