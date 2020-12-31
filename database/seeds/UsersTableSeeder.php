<?php

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
        // Criar 10 usuários utilizando o factory User
        factory(App\User::class, 10)->create();
    }
}
