<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * El autor del comentario, el autor de la reseña dueña del hilo,
     * o un admin pueden borrar el comentario.
     */
    public function delete(User $user, Comment $comment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->id === $comment->user_id) {
            return true;
        }

        return $user->id === $comment->review->user_id;
    }
}
