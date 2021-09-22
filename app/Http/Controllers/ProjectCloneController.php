<?php

namespace App\Http\Controllers;

use App\Jobs\ProjectCloneJob;
use App\Models\RefApprovalLevel;
use App\Models\RefBasis;
use App\Models\RefCipType;
use App\Models\RefCovidIntervention;
use App\Models\RefFsStatus;
use App\Models\RefFundingInstitution;
use App\Models\RefFundingSource;
use App\Models\RefGad;
use App\Models\RefImplementationMode;
use App\Models\RefInfrastructureSector;
use App\Models\Office;
use App\Models\OperatingUnitType;
use App\Models\RefPapType;
use App\Models\RefPdpChapter;
use App\Models\RefPdpIndicator;
use App\Models\RefPipTypology;
use App\Models\RefPreparationDocument;
use App\Models\Project;
use App\Models\RefProjectStatus;
use App\Models\RefRegion;
use App\Models\RefSdg;
use App\Models\RefSpatialCoverage;
use App\Models\RefTenPointAgenda;
use App\Models\RefTier;
use Illuminate\Http\Request;

class ProjectCloneController extends Controller
{
    public function show(Project $project, string $uuid = null)
    {
        if ($uuid) {
            $project = $project->clones()->where('uuid', $uuid)->firstOrFail();
        }

        $project->load(
            'regions',
            'region_investments.region',
            'region_infrastructures.region',
            'fs_investments.funding_source',
            'fs_infrastructures.funding_source',
            'bases',
            'disbursement',
            'nep',
            'allocation',
            'feasibility_study',
            'right_of_way',
            'resettlement_action_plan',
            'ten_point_agendas',
            'sdgs',
            'pdp_chapters',
            'pdp_indicators',
            'operating_units');

        return view('projects.show', compact('project'))
            ->with([
                'offices'                   => Office::all(),
                'pap_types'                 => RefPapType::all(),
                'bases'                     => RefBasis::all(),
                'project_statuses'          => RefProjectStatus::all(),
                'spatial_coverages'         => RefSpatialCoverage::all(),
                'regions'                   => RefRegion::all(),
                'gads'                      => RefGad::all(),
                'pip_typologies'            => RefPipTypology::all(),
                'cip_types'                 => RefCipType::all(),
                'years'                     => config('ipms.editor.years'),
                'approval_levels'           => RefApprovalLevel::all(),
                'infrastructure_sectors'    => RefInfrastructureSector::with('children')->get(),
                'pdp_chapters'              => RefPdpChapter::orderBy('name')->get(),
                'sdgs'                      => RefSdg::all(),
                'ten_point_agendas'         => RefTenPointAgenda::all(),
                'pdp_indicators'            => RefPdpIndicator::with('children.children.children')
                    ->where('level',1)
                    ->select('id','name')->get(),
                'funding_sources'           => RefFundingSource::all(),
                'funding_institutions'      => RefFundingInstitution::all(),
                'implementation_modes'      => RefImplementationMode::all(),
                'tiers'                     => RefTier::all(),
                'preparation_documents'     => RefPreparationDocument::all(),
                'fs_statuses'               => RefFsStatus::all(),
                'ou_types'                  => OperatingUnitType::with('operating_units')->get(),
                'covidInterventions'        => RefCovidIntervention::all(),
            ]);
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $this->validate($request, [
            'branch_id' => 'required|exists:branches,id'
        ]);

        // check updating period id
        if ($project->branch_id == $request->branch_id) {
            return back()->with('error','This project has already been cloned to this updating period');
        }

        $projectAlreadyCloned = Project::where('project_id', $project->id)
            ->where('branch_id', $request->branch_id)
            ->exists();

        if ($projectAlreadyCloned) {
            return back()->with('error','This project has already been cloned to this updating period');
        }

        dispatch(new ProjectCloneJob($project->id, $request->updating_period_id ?? config('ipms.current_updating_period'), auth()->id()));

        return back()->with('success','Successfully began cloning project. This may take some time.');
    }
}
