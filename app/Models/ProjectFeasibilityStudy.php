<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Venturecraft\Revisionable\RevisionableTrait;

class ProjectFeasibilityStudy extends Model
{
    use HasFactory;
    use RevisionableTrait;

    protected $casts = [
        'needs_assistance' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
        'completion_date' => 'datetime:Y-m-d',
        'fs_start_date' => 'datetime:Y-m-d',
        'fs_end_date' => 'datetime:Y-m-d',
    ];

    protected $fillable = [
        'project_id',
        'fs_status_id',
        'needs_assistance',
        'y2016',
        'y2017',
        'y2018',
        'y2019',
        'y2020',
        'y2021',
        'y2022',
        'y2023',
        'y2024',
        'y2025',
        'y2026',
        'y2027',
        'y2028',
        'y2029',
        'completion_date',
    ];

    protected $hidden = ['project_id'];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function fs_status(): BelongsTo
    {
        return $this->belongsTo(RefFsStatus::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getTotalAttribute(): float
    {
        return floatval($this->y2017)
            + floatval($this->y2018)
            + floatval($this->y2019)
            + floatval($this->y2020)
            + floatval($this->y2021)
            + floatval($this->y2022);
    }
}
