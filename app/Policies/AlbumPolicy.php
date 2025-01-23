<?php

namespace App\Policies;

use App\Models\Album;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AlbumPolicy
{
   
        public function update(User $user, Album $album)
        {
            return $user->id === $album->user_id;
        }
    
        public function delete(User $user, Album $album)
        {
            return $user->id === $album->user_id;
        }

    
}
