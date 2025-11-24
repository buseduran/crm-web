<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CardReadLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class CardReadLogPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CardReadLog');
    }

    public function view(AuthUser $authUser, CardReadLog $cardReadLog): bool
    {
        return $authUser->can('View:CardReadLog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CardReadLog');
    }

    public function update(AuthUser $authUser, CardReadLog $cardReadLog): bool
    {
        return $authUser->can('Update:CardReadLog');
    }

    public function delete(AuthUser $authUser, CardReadLog $cardReadLog): bool
    {
        return $authUser->can('Delete:CardReadLog');
    }

    public function restore(AuthUser $authUser, CardReadLog $cardReadLog): bool
    {
        return $authUser->can('Restore:CardReadLog');
    }

    public function forceDelete(AuthUser $authUser, CardReadLog $cardReadLog): bool
    {
        return $authUser->can('ForceDelete:CardReadLog');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CardReadLog');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CardReadLog');
    }

    public function replicate(AuthUser $authUser, CardReadLog $cardReadLog): bool
    {
        return $authUser->can('Replicate:CardReadLog');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CardReadLog');
    }

}