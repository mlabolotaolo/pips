<?php

namespace App\Http\Livewire;

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
use Livewire\Component;

class ProjectEdit extends Component
{
    public $project;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        return view('livewire.project-edit')
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
}
