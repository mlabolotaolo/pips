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
use App\Models\RefInfrastructureSubsector;
use App\Models\Office;
use App\Models\RefOperatingUnit;
use App\Models\OperatingUnitType;
use App\Models\RefPapType;
use App\Models\RefPdpChapter;
use App\Models\RefPipTypology;
use App\Models\RefPreparationDocument;
use App\Models\RefPrerequisite;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AdminTable extends Component
{
    protected $queryString = ['label','q','sort'];

    public $labelId;

    public $name;

    public $description;

    public $labelType;

    public $label = 'approval_levels';

    public $q;

    public $sort;

    public $showForm = false;

    public $flash;

    public $labelToEdit = [
        'labelId' => null,
        'name' => null,
        'description' => null,
    ];

    public $labelTypes = [
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

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description'   => 'nullable|string|max:255',
            'labelType' => ['required', Rule::in($this->labelTypes->keys())],
        ];
    }

    public function create()
    {
        $this->showForm = true;

        $this->labelId      = null;
        $this->name         = null;
        $this->description  = null;
        $this->labelType    = $this->label;
    }

    public function edit($label)
    {
        $this->showForm     = true;

        $this->labelId      = $label['id'];
        $this->name         = $label['name'];
        $this->description  = $label['description'];
        $this->labelType    = $this->label;
    }

    public function submit()
    {
        $this->labelTypes[$this->labelType]::updateOrCreate([
            'id' => $this->labelId
        ],
        [
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->showForm = false;
    }

    public function delete($labelId)
    {
        $label = $this->labelTypes[$this->label]::find($labelId);

        $label->delete();
    }

    public function render()
    {
        return view('livewire.admin-table', [
            'labelTypes' => $this->labelTypes,
            'labels'     => empty($this->q)
                ? $this->labelTypes[$this->label]::all()
                : $this->labelTypes[$this->label]::where('name','like', '%'. $this->q . '%')
                    ->orWhere('description','like', '%'. $this->q . '%')
                    ->get(),
        ]);
    }
}
