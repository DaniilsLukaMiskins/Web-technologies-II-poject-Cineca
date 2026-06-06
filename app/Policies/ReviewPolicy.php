<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    // admin can do everything
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    // only author can redact
    public function update(User $user, Review $review): bool
    {
        return $user->id === $review->user_id;
    }

    // author or moderator can delete
    public function delete(User $user, Review $review): bool
    {
        return $user->id === $review->user_id 
            || $user->isModerator();
    }
}