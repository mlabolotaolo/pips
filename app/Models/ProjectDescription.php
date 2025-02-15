<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Venturecraft\Revisionable\RevisionableTrait;

class ProjectDescription extends Model
{
    use HasFactory;
    use RevisionableTrait;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
    ];

    protected $fillable = [
        'description',
    ];

    protected $hidden = ['deleted_at','project_id'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
