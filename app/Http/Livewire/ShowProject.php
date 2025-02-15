<?php

namespace App\Http\Livewire;

use App\Models\RefApprovalLevel;
use App\Models\RefBasis;
use App\Models\RefCovidIntervention;
use App\Models\RefFsStatus;
use App\Models\RefFundingInstitution;
use App\Models\RefFundingSource;
use App\Models\RefGad;
use App\Models\RefImplementationMode;
use App\Models\Office;
use App\Models\RefPapType;
use App\Models\RefPdpChapter;
use App\Models\RefPreparationDocument;
use App\Models\Project;
use App\Models\RefProjectStatus;
use App\Models\RefRegion;
use App\Models\RefSdg;
use App\Models\RefSpatialCoverage;
use App\Models\RefTenPointAgenda;
use App\Models\RefTier;
use Livewire\Component;

class ShowProject extends Component
{
    public $projectId;

    public $project;

    public $officeId;

    public $papTypeId;

    public $regularProgram;

    public $hasInfra;

    public $projectBases = [];

    public $description;

    public $expectedOutputs;

    public $totalProjectCost;

    public $projectStatus;

    public $research;

    public $ict;

    public $covid;

    public $covidInterventions = [];

    public $spatialCoverage;

    public $projectRegions = [];

    public $targetStartYear;

    public $targetEndYear;

    public $iccable;

    public $approvalLevel;

    public $approvalDate;

    public $gad;

    public $rdip;

    public $rdcEndorsementRequired;

    public $rdcEndorsed;

    public $rdcEndorsedDate;

    public $preparationDocument;

    public $hasFs;

    public $fsStatus;

    public $needAssistance;

    public $fsY2017;

    public $fsY2018;

    public $fsY2019;

    public $fsY2020;

    public $fsY2021;

    public $fsY2022;

    public $fsTotal;

    public $employmentGenerated;

    public $pdpChapter;

    public $pdpChapters = [];

    public $sdgs = [];

    public $tenPointAgendas = [];

    public $fundingSource;

    public $fundingSources = [];

    public $otherFs;

    public $fundingInstitution;

    public $implementationMode;

    public $tier;

    public $uacsCode;

    public $updates;

    public $updatesDate;

    public $fsInvestments = [];

    public $regionInvestments = [];

    public $nep = [];

    public $allocation = [];

    public $disbursement = [];

    public $regionTotals = [
        'y2016' => 0,
        'y2017' => 0,
        'y2018' => 0,
        'y2019' => 0,
        'y2020' => 0,
        'y2021' => 0,
        'y2022' => 0,
        'y2023' => 0,
    ];

    public $fsTotals = [
        'y2016' => 0,
        'y2017' => 0,
        'y2018' => 0,
        'y2019' => 0,
        'y2020' => 0,
        'y2021' => 0,
        'y2022' => 0,
        'y2023' => 0,
    ];

    public $booleanOptions = [
        '0' => 'No',
        '1' => 'Yes',
    ];

    protected $rules = [
        'projectBases.*'        => 'nullable|array|exists:bases,id',
        'covidInterventions.*'  => 'nullable|array|exists:covid_interventions,id',
        'projectRegions.*'      => 'nullable|array|exists:regions,id',
        'pdpChapters.*'         => 'nullable|array|exists:pdp_chapters,id',
        'sdgs.*'                => 'nullable|array|exists:sdgs,id',
        'tenPointAgendas.*'     => 'nullable|array|exists:ten_point_agendas,id',
        'fundingSources.*'      => 'nullable|array|exists:funding_sources,id',
        'fsInvestments.*.fs_id' => 'required|exists:funding_sources,id',
        'fsInvestments.*.y2016' => 'required|numeric|min:0',
        'fsInvestments.*.y2017' => 'required|numeric|min:0',
        'fsInvestments.*.y2018' => 'required|numeric|min:0',
        'fsInvestments.*.y2019' => 'required|numeric|min:0',
        'fsInvestments.*.y2020' => 'required|numeric|min:0',
        'fsInvestments.*.y2021' => 'required|numeric|min:0',
        'fsInvestments.*.y2022' => 'required|numeric|min:0',
        'fsInvestments.*.y2023' => 'required|numeric|min:0',
        'regionInvestments.*.region_id' => 'required|exists:regions,id',
        'regionInvestments.*.y2016' => 'required|numeric|min:0',
        'regionInvestments.*.y2017' => 'required|numeric|min:0',
        'regionInvestments.*.y2018' => 'required|numeric|min:0',
        'regionInvestments.*.y2019' => 'required|numeric|min:0',
        'regionInvestments.*.y2020' => 'required|numeric|min:0',
        'regionInvestments.*.y2021' => 'required|numeric|min:0',
        'regionInvestments.*.y2022' => 'required|numeric|min:0',
        'regionInvestments.*.y2023' => 'required|numeric|min:0',
        'allocation.y2016' => 'required|numeric|min:0',
        'allocation.y2017' => 'required|numeric|min:0',
        'allocation.y2018' => 'required|numeric|min:0',
        'allocation.y2019' => 'required|numeric|min:0',
        'allocation.y2020' => 'required|numeric|min:0',
        'allocation.y2021' => 'required|numeric|min:0',
        'allocation.y2022' => 'required|numeric|min:0',
        'allocation.y2023' => 'required|numeric|min:0',
        'nep.y2016' => 'required|numeric|min:0',
        'nep.y2017' => 'required|numeric|min:0',
        'nep.y2018' => 'required|numeric|min:0',
        'nep.y2019' => 'required|numeric|min:0',
        'nep.y2020' => 'required|numeric|min:0',
        'nep.y2021' => 'required|numeric|min:0',
        'nep.y2022' => 'required|numeric|min:0',
        'nep.y2023' => 'required|numeric|min:0',
        'disbursement.y2016' => 'required|numeric|min:0',
        'disbursement.y2017' => 'required|numeric|min:0',
        'disbursement.y2018' => 'required|numeric|min:0',
        'disbursement.y2019' => 'required|numeric|min:0',
        'disbursement.y2020' => 'required|numeric|min:0',
        'disbursement.y2021' => 'required|numeric|min:0',
        'disbursement.y2022' => 'required|numeric|min:0',
        'disbursement.y2023' => 'required|numeric|min:0',
    ];

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->projectId = $project->id;
        $this->officeId = $project->office_id;
        $this->papTypeId = $project->pap_type_id;
        $this->regularProgram = $project->regular_program;
        $this->hasInfra = $project->has_infra;
        $this->projectBases    = array_map('strval',$project->bases()->pluck('id')->toArray() ?? []);
        $this->description = $project->description->description ?? '';
        $this->expectedOutputs = $project->expected_output->expected_outputs ?? '';
        $this->totalProjectCost = $project->total_project_cost ?? 0;
        $this->projectStatus = $project->project_status_id;
        $this->research = $project->research;
        $this->ict = $project->ict;
        $this->covid = $project->covid;
        $this->covidInterventions    = array_map('strval',$project->covid_interventions()->pluck('id')->toArray() ?? []);
        $this->spatialCoverage = $project->spatial_coverage_id;
        $this->projectRegions    = array_map('strval',$project->regions()->pluck('id')->toArray() ?? []);
        $this->targetStartYear = $project->target_start_year;
        $this->targetEndYear = $project->target_end_year;
        $this->iccable = $project->iccable;
        $this->approvalLevel = $project->approval_level_id;
        $this->approvalDate = $project->approval_date;
        $this->gad = $project->gad_id;
        $this->rdip = $project->rdip;
        $this->rdcEndorsementRequired = $project->rdc_endorsement_required;
        $this->rdcEndorsed = $project->rdc_endorsed;
        $this->rdcEndorsedDate = $project->rdc_endorsed_date;
        $this->preparationDocument = $project->preparation_document_id;
        $this->hasFs = $project->has_fs;
        $this->fsStatus = $project->feasibility_study->fs_status_id ?? null;
        $this->needAssistance = $project->feasibility_study->need_assistance ?? false;
        $this->fsY2017 = $project->feasibility_study->y2017 ?? 0;
        $this->fsY2018 = $project->feasibility_study->y2018 ?? 0;
        $this->fsY2019 = $project->feasibility_study->y2019 ?? 0;
        $this->fsY2020 = $project->feasibility_study->y2020 ?? 0;
        $this->fsY2021 = $project->feasibility_study->y2021 ?? 0;
        $this->fsY2022 = $project->feasibility_study->y2022 ?? 0;
        $this->employmentGenerated = $project->employment_generated;
        $this->pdpChapter = $project->pdp_chapter_id;
        $this->pdpChapters = array_map('strval', $project->pdp_chapters()->pluck('id')->toArray() ?? []);
        $this->sdgs = array_map('strval', $project->sdgs()->pluck('id')->toArray() ?? []);
        $this->tenPointAgendas = array_map('strval', $project->ten_point_agendas()->pluck('id')->toArray() ?? []);
        $this->fundingSource = $project->funding_source_id;
        $this->fundingSources = array_map('strval', $project->funding_sources()->pluck('id')->toArray() ?? []);
        $this->otherFs = $project->other_fs;
        $this->implementationMode = $project->implementation_mode_id;
        $this->fundingInstitution = $project->funding_institution_id;
        $this->uacsCode = $project->uacs_code;
        $this->updates = $project->project_update->updates ?? '';
        $this->updatesDate = $project->project_update->updates_date ?? '';
        $this->fsInvestments = $project->fs_investments;
        $this->regionInvestments = $project->region_investments;
        $this->nep = $project->nep;
        $this->allocation = $project->allocation;
        $this->disbursement = $project->disbursement;

        $this->computeFsInvestmentsTotal();
        $this->computeRegionInvestmentsTotal();
    }

    public function updateOffice()
    {
        $project = $this->project;
        $project->office_id = $this->officeId;
        $project->save();
    }

    public function updatePapType()
    {
        $project = $this->project;
        $project->pap_type_id = $this->papTypeId;
        if ($project->pap_type_id == 2) {
            $project->regular_program = false;
        } else {
            $project->regular_program = $this->regularProgram;
        }

        $project->save();
    }

    public function updateHasInfra()
    {
        $project = $this->project;
        $project->has_infra = $this->hasInfra;
        $project->save();
    }

    public function updateProjectBases()
    {
        $project = $this->project;
        $project->bases()->sync(array_diff($this->projectBases,[false]));
    }

    public function updateDescription()
    {
        $project = $this->project;
        $project->description()->updateOrCreate([
            'project_id' => $project->id,
        ],
        [
            'description' => $this->description
        ]);
    }

    public function updateExpectedOutputs()
    {
        $project = $this->project;
        $project->expected_output()->updateOrCreate([
            'project_id' => $project->id,
        ],
        [
            'expected_outputs' => $this->expectedOutputs
        ]);
    }

    public function updateTotalProjectCost()
    {
        $project = $this->project;
        $project->total_project_cost = toFloat($this->totalProjectCost);
        $project->save();
    }

    public function updateProjectStatus()
    {
        $project = $this->project;
        $project->project_status_id = $this->projectStatus;
        $project->save();
    }

    public function updateResearch()
    {
        $project = $this->project;
        $project->research = $this->research;
        $project->save();
    }

    public function updateIct()
    {
        $project = $this->project;
        $project->ict = $this->ict;
        $project->save();
    }

    public function updateCovid()
    {
        $project = $this->project;
        $project->covid = $this->covid;
        $project->save();
    }

    public function updateCovidInterventions()
    {
        $project = $this->project;
        $project->covid_interventions()->sync(array_diff($this->covidInterventions,[false]));
    }

    public function updateSpatialCoverage()
    {
        $project = $this->project;
        $project->spatial_coverage_id = $this->spatialCoverage;
        $project->save();
    }

    public function updateRegions()
    {
        $this->project->regions()->sync(array_map('intval', $this->projectRegions));
    }

    public function updateTargetStartYear()
    {
        $project = $this->project;
        $project->target_start_year = $this->targetStartYear;
        $project->save();
    }

    public function updateTargetEndYear()
    {
        $project = $this->project;
        $project->target_end_year = $this->targetEndYear;
        $project->save();
    }

    public function updateIccable()
    {
        $project = $this->project;
        $project->iccable = $this->iccable;
        $project->save();
    }

    public function updateApprovalLevel()
    {
        $project = $this->project;
        $project->approval_level_id = $this->approvalLevel;
        $project->save();
    }

    public function updateApprovalDate()
    {
        $project = $this->project;
        $project->approval_date = $this->approvalDate;
        $project->save();
    }

    public function updateGad()
    {
        $project = $this->project;
        $project->gad_id = $this->gad;
        $project->save();
    }

    public function updateRdip()
    {
        $project = $this->project;
        $project->rdip = $this->rdip;
        $project->save();
    }

    public function updateRdcEndorsementRequired()
    {
        $project = $this->project;
        $project->rdc_endorsement_required = $this->rdcEndorsementRequired;
        $project->save();
    }

    public function updateRdcEndorsed()
    {
        $project = $this->project;
        $project->rdc_endorsed = $this->rdcEndorsed;
        $project->save();
    }

    public function updateRdcEndorsedDate()
    {
        $project = $this->project;
        $project->rdc_endorsed_date = $this->rdcEndorsedDate;
        $project->save();
    }

    public function updatePreparationDocument()
    {
        $project = $this->project;
        $project->preparation_document_id = $this->preparationDocument;
        $project->save();
    }

    public function updateHasFs()
    {
        $project = $this->project;
        $project->has_fs = $this->hasFs;
        $project->save();
    }

    public function updateFsStatus()
    {
        $project = $this->project;
        $project->feasibility_study->updateOrCreate([
            'project_id' => $project->id
        ],[
            'fs_status_id' => $this->fsStatus,
        ]);
    }

    public function updateNeedAssistance()
    {
        $project = $this->project;
        $project->feasibility_study->updateOrCreate([
            'project_id' => $project->id
        ],[
            'need_assistance' => $this->needAssistance,
        ]);
    }

    public function updateFsCost()
    {
        $project = $this->project;
        $project->feasibility_study->updateOrCreate([
            'project_id' => $project->id
        ],[
            'y2017' => $this->fsY2017,
            'y2018' => $this->fsY2018,
            'y2019' => $this->fsY2019,
            'y2020' => $this->fsY2020,
            'y2021' => $this->fsY2021,
            'y2022' => $this->fsY2022,
        ]);
    }

    public function updateEmploymentGenerated()
    {
        $project = $this->project;
        $project->employment_generated = $this->employmentGenerated;
        $project->save();
    }

    public function updatePdpChapter()
    {
        $project = $this->project;
        $project->pdp_chapter_id = $this->pdpChapter;
        $project->save();
    }

    public function updatePdpChapters()
    {
        $project = $this->project;
        $project->pdp_chapters()->sync(array_map('intval', $this->pdpChapters));
    }

    public function updateSdgs()
    {
        $project = $this->project;
        $project->sdgs()->sync(array_map('intval', $this->sdgs));
    }

    public function updateTpas()
    {
        $project = $this->project;
        $project->ten_point_agendas()->sync(array_map('intval', $this->tenPointAgendas));
    }

    public function updateFundingSource() {
        $project = $this->project;
        $project->funding_source_id = $this->fundingSource;
        $project->save();
    }

    public function updateFundingSources() {
        $project = $this->project;
        $project->funding_sources()->sync(array_map('intval', $this->fundingSources));
    }

    public function updateOtherFs() {
        $project = $this->project;
        $project->other_fs = $this->otherFs;
        $project->save();
    }

    public function updateImplementationMode()
    {
        $project = $this->project;
        $project->implementation_mode_id = $this->implementationMode;
        $project->save();
    }

    public function updateFundingInstitution() {
        $project = $this->project;
        $project->funding_institution_id = $this->fundingInstitution;
        $project->save();
    }

    public function updateTier() {
        $project = $this->project;
        $project->tier_id = $this->tier;
        $project->save();
    }

    public function updateUacsCode() {
        $project = $this->project;
        $project->uacs_code = $this->uacsCode;
        $project->save();
    }

    public function updateUpdates() {
        $project = $this->project;
        $project->project_update()->updateOrCreate([
            'project_id' => $project->id,
        ],[
            'updates' => $this->updates,
        ]);
    }

    public function updateUpdatesDate() {
        $project = $this->project;
        $project->project_update()->updateOrCreate([
            'project_id' => $project->id,
        ],[
            'updates_date' => $this->updatesDate,
        ]);
    }

    public function updateFsInvestments()
    {
        foreach ($this->fsInvestments as $fsInvestment) {
            $this->project->fs_investments()->updateOrCreate([
                'fs_id' => $fsInvestment->fs_id
            ],[
                'y2016' => $fsInvestment->y2016,
                'y2017' => $fsInvestment->y2017,
                'y2018' => $fsInvestment->y2018,
                'y2019' => $fsInvestment->y2019,
                'y2020' => $fsInvestment->y2020,
                'y2021' => $fsInvestment->y2021,
                'y2022' => $fsInvestment->y2022,
                'y2023' => $fsInvestment->y2023,
            ]);
        }
    }

    public function updateRegionInvestments()
    {
        foreach ($this->regionInvestments as $regionInvestment) {
            $this->project->region_investments()->updateOrCreate([
                'region_id' => $regionInvestment->region_id
            ],[
                'y2016' => $regionInvestment->y2016,
                'y2017' => $regionInvestment->y2017,
                'y2018' => $regionInvestment->y2018,
                'y2019' => $regionInvestment->y2019,
                'y2020' => $regionInvestment->y2020,
                'y2021' => $regionInvestment->y2021,
                'y2022' => $regionInvestment->y2022,
                'y2023' => $regionInvestment->y2023,
            ]);
        }
    }

    public function updatedRegionInvestments()
    {
        $this->computeRegionInvestmentsTotal();
    }

    public function computeRegionInvestmentsTotal()
    {
        $this->regionTotals = collect($this->regionInvestments)->reduce(function ($total, $item) {
            $total['y2016'] += $item['y2016'];
            $total['y2017'] += $item['y2017'];
            $total['y2018'] += $item['y2018'];
            $total['y2019'] += $item['y2019'];
            $total['y2020'] += $item['y2020'];
            $total['y2021'] += $item['y2021'];
            $total['y2022'] += $item['y2022'];
            $total['y2023'] += $item['y2023'];
            return $total;
        }, [
            'y2016' => 0,
            'y2017' => 0,
            'y2018' => 0,
            'y2019' => 0,
            'y2020' => 0,
            'y2021' => 0,
            'y2022' => 0,
            'y2023' => 0,
        ]);
    }

    public function updatedFsInvestments()
    {
        $this->computeFsInvestmentsTotal();
    }

    public function computeFsInvestmentsTotal()
    {
        $this->fsTotals = collect($this->fsInvestments)->reduce(function ($total, $item) {
            $total['y2016'] += $item['y2016'];
            $total['y2017'] += $item['y2017'];
            $total['y2018'] += $item['y2018'];
            $total['y2019'] += $item['y2019'];
            $total['y2020'] += $item['y2020'];
            $total['y2021'] += $item['y2021'];
            $total['y2022'] += $item['y2022'];
            $total['y2023'] += $item['y2023'];
            return $total;
        }, [
            'y2016' => 0,
            'y2017' => 0,
            'y2018' => 0,
            'y2019' => 0,
            'y2020' => 0,
            'y2021' => 0,
            'y2022' => 0,
            'y2023' => 0,
        ]);
    }

    public function render()
    {
        return view('livewire.show-project',[
            'offices'   => Office::select('id','acronym')->get(),
            'pap_types' => RefPapType::all(),
            'bases'     => RefBasis::all(),
            'project_statuses' => RefProjectStatus::all(),
            'covid_interventions' => RefCovidIntervention::all(),
            'spatial_coverages' => RefSpatialCoverage::all(),
            'region_options' => RefRegion::all(),
            'years' => config('ipms.editor.years'),
            'approval_levels' => RefApprovalLevel::all(),
            'gads' => RefGad::all(),
            'preparation_documents' => RefPreparationDocument::all(),
            'fs_statuses' => RefFsStatus::all(),
            'pdp_chapters' => RefPdpChapter::all(),
            'sdg_options' => RefSdg::all(),
            'ten_point_agendas' => RefTenPointAgenda::all(),
            'funding_sources' => RefFundingSource::all(),
            'implementation_modes' => RefImplementationMode::all(),
            'funding_institutions' => RefFundingInstitution::all(),
            'tiers' => RefTier::all(),
        ]);
    }
}
