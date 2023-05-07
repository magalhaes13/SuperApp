<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Todo;
use App\Models\TodoTask;
use Illuminate\Database\Eloquent\Factories\HasFactory;




class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function ($user) {
            $user->todos()->saveMany(
               Todo::factory(10)->make()
            )->each(function ($todo) {
                $todo->tasks()->saveMany(
                    TodoTask::factory(10)->make()
                );
            });
        });
    }
}
