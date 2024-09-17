<?php

namespace App\Policies;

use App\Models\{Project, User};

class ProjectPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }
}
