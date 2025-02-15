<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasUuid;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefProjectStatus extends Model
{
    use HasFactory;
    use Sluggable;
    use Auditable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected $appends = [
//        'count',
    ];

    protected $hidden = [
        'slug',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function identifiableName()
    {
        return $this->name;
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function getCountAttribute(): int
    {
        return $this->projects->count();
    }

    /**
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
