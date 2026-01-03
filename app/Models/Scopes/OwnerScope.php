<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class OwnerScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        // If no user logged in, do nothing
        if (! $user) {
            return;
        }

        // If admin, do not apply scope
        if ($user->admin) {
            return;
        }

        // Otherwise, restrict to owner
        $builder->where('user_id', $user->id);
    }
}
