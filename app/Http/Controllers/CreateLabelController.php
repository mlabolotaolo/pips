<?php

namespace App\Http\Controllers;

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
use App\Models\RefInfrastructureSubsector;
use App\Models\OperatingUnitType;
use App\Models\RefPapType;
use App\Models\RefPdpChapter;
use App\Models\RefPipTypology;
use App\Models\RefPreparationDocument;
use App\Models\RefPrerequisite;
use Illuminate\Http\Request;

class CreateLabelController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            'name' => 'required',
            'labelType' => 'required|in:'. implode(',',array_keys($this->labelTypes)),
        ]);

        $this->labelTypes[$request->labelType]::updateOrCreate([
                'id' => $request->id
            ],
            [
                'name' => $request->name,
                'description' => $request->description,
        ]);

        return back();
    }

    protected $labelTypes = [
        'approval_levels'               => RefApprovalLevel::class,
        'bases'                         => RefBasis::class,
        'cip_types'                     => RefCipType::class,
        'covid_interventions'           => RefCovidIntervention::class,
        'fs_statuses'                   => RefFsStatus::class,
        'funding_institutions'          => RefFundingInstitution::class,
        'funding_sources'               => RefFundingSource::class,
        'gads'                          => RefGad::class,
        'implementation_modes'          => RefImplementationMode::class,
        'infrastructure_sector'         => RefInfrastructureSector::class,
        'infrastructure_subsectors'     => RefInfrastructureSubsector::class,
//        'offices'                       => Office::class,
//        'operating_units'               => OperatingUnit::class,
        'operating_unit_types'          => OperatingUnitType::class,
        'pap_types'                     => RefPapType::class,
        'pdp_chapters'                  => RefPdpChapter::class,
        'pip_typologies'                => RefPipTypology::class,
        'preparation_documents'         => RefPreparationDocument::class,
        'prerequisites'                 => RefPrerequisite::class,
    ];

}
