<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use PhpParser\Node\Stmt\TryCatch;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    
    public function run(): void
    {
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
        User::truncate();

        for($l = 0; $l<=5; $l ++){
            $user = User::factory(500000)->create();
            $chunks = $user->chunk(5000);

            $chunks->each(function($chunk){
                User::insert($chunk->toArray());
            });
        }
    }
}
