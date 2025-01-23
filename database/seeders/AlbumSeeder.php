<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Album;
use App\Models\User;

class AlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        $user = User::first();

        if ($user) {
          
            Album::create([
                'user_id' => $user->id,
                'name' => 'Your First Test Album',
            ]);
        }
    }
}
