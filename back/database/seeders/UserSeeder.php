<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run()
        {


            // User::factory()->count(3)->create(); criar usuarios fakes

            // factory(User::class)->create([
            //     'first_name' => 'Arthur',
            //     'last_name'  => 'Magalhaes',
            //     'email'      => 'teste@teste.com',
            //     'password'   => bcrypt('Password1'),
            // ]);

            User::create ([
                'first_name' => 'Arthur',
                'last_name'  => 'Magalhaes',
                'email'      => 'teste@teste.com',
                'password'   => bcrypt('Password1'),
            ]);
    }
}
