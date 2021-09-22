<?php

namespace App\Jobs;

use App\Models\RefFundingSource;
use App\Models\Project;
use App\Models\RefRegion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateProjectRelationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $project;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($projectId)
    {
        $this->project = Project::find($projectId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $project = $this->project;

        foreach (RefFundingSource::all() as $fs) {
            $project->fs_investments()->create(['fs_id' => $fs->id]);
        }

        foreach (RefRegion::all() as $region) {
            $project->region_investments()->create(['region_id' => $region->id]);
        }

        $project->project_update()->create([
            'updates'   => '',
            'updates_date' => '',
        ]);

        $project->expected_output()->create([
            'expected_outputs' => ''
        ]);

        $project->description()->create([
            'description' => ''
        ]);
        $project->feasibility_study()->create([]);
        $project->nep()->create([]);
        $project->allocation()->create([]);
        $project->disbursement()->create([]);
    }
}
