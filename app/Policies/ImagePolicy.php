<?php

namespace App\Policies;

use App\Models\Image;
use App\Models\User;

class ImagePolicy
{
    public function viewAny(User $user): true
    {
        return true;
    }

    public function view(User $user, Image $image): bool
    {
        if ($image->user_id == $user->id) return true;

        return false;
    }

    public function create(User $user): true
    {
        return true;
    }

    public function update(User $user, Image $image): bool
    {
        if ($image->user_id == $user->id) return true;

        return false;
    }

    public function delete(User $user, Image $image): bool
    {
        if ($image->user_id == $user->id) return true;

        return false;
    }
}
