<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OuInfrastructure extends Model
{
    use HasFactory;
    use HasUuid;

    protected $touches = [
        'project'
    ];

    protected $fillable = [
        'project_id',
        'ou_id',
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
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function operating_unit(): BelongsTo
    {
        return $this->belongsTo(RefOperatingUnit::class,'ou_id','id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
