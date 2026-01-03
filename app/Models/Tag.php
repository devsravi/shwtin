<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class Tag extends Model
{
    /**
     * Get all of the urls that are assigned this tag.
     */
    public function urls(): MorphToMany
    {
        return $this->morphedByMany(Url::class, 'taggable');
    }
}
