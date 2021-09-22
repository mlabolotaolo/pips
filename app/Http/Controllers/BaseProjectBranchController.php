<?php

namespace App\Http\Controllers;

use App\Jobs\ProjectCloneJob;
use App\Models\RefApprovalLevel;
use App\Models\BaseProject;
use App\Models\RefBasis;
use App\Models\RefBranch;
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
use App\Models\RefReadinessLevel;
use App\Models\RefRegion;
use App\Models\RefSdg;
use App\Models\RefSpatialCoverage;
use App\Models\RefTenPointAgenda;
use App\Models\RefTier;
use Illuminate\Http\Request;

class BaseProjectBranchController extends Controller
{
    public function index(BaseProject $baseProject)
    {
        // get the default project whish is the same as the system's default branch
        $project = $baseProject->projects()->where('branch_id', config('ipms.default_branch'))->first();

        return view('projects.show', compact(['baseProject','project']))
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

    public function show(BaseProject $baseProject, RefBranch $branch)
    {
        $project = $this->getProject($baseProject, $branch);

        if (! $project) {
            return redirect()->route('base-projects.branches.index', $baseProject)
                ->with('error', $branch->name . ' does not exist yet for this project.');
        }

        return view('projects.show', compact(['baseProject','project']))
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

    public function store(Request $request, BaseProject $baseProject)
    {
        $this->validate($request, [
            'branch_id' => 'required|exists:branches,id'
        ]);

        $projectExists = $baseProject->projects()->where('branch_id', $request->branch_id)->first();

        // check updating period id
        if ($projectExists) {
            return back()->with('error','This project already has a similar branch');
        }

        dispatch(new ProjectCloneJob($baseProject->id, $request->branch_id, auth()->id()));

        return back()->with('success','Successfully began cloning project. This may take some time.');
    }

    public function review(BaseProject $baseProject, RefBranch $branch)
    {
        $project = $this->getProject($baseProject, $branch);
        $review = $project->review;
        $pip_typologies = RefPipTypology::all();
        $cip_types = RefCipType::all();
        $readiness_levels = RefReadinessLevel::all();

        return view('projects.reviews.index', compact(['baseProject','project','review','pip_typologies','cip_types','readiness_levels']));
    }

    public function trips(BaseProject $baseProject, string $branch)
    {

    }

    public function getProject($baseProject, $branch)
    {
        return $baseProject->projects()->where('branch_id', $branch->id)->first();
    }

    public function history(BaseProject $baseProject, RefBranch $branch)
    {
        $project = $this->getProject($baseProject, $branch);

        $history = $project->revisionHistory()->latest()->get();
        $history = $history->merge($project->description->revisionHistory()->latest()->get());
        $history = $history->merge($project->expected_output->revisionHistory()->latest()->get());
        $history = $history->merge($project->nep->revisionHistory()->latest()->get());
        $history = $history->merge($project->allocation->revisionHistory()->latest()->get());
        $history = $history->merge($project->disbursement->revisionHistory()->latest()->get());
        $history = $history->merge($project->feasibility_study->revisionHistory()->latest()->get());
        $history = $history->merge($project->project_update->revisionHistory()->latest()->get());

        foreach ($project->region_investments as $ri) {
            $history = $history->merge($ri->revisionHistory()->latest()->get());
        }

        foreach ($project->fs_investments as $fsi) {
            $history = $history->merge($fsi->revisionHistory()->latest()->get());
        }

        $history = $history->sortByDesc('created_at');

        return view('projects.history', compact(['baseProject','branch','project','history']));
    }

    public function issues(BaseProject $baseProject, RefBranch $branch)
    {
        $project = $this->getProject($baseProject, $branch);
        $issues = $project->issues;

        return view('projects.issues.index', compact(['baseProject','branch','project','issues']));
    }

    public function createIssue(BaseProject $baseProject, RefBranch $branch)
    {
        $project = $this->getProject($baseProject, $branch);
        $issues = $project->issues;

        return view('projects.issues.create', compact(['baseProject','branch','project','issues']));
    }
}
