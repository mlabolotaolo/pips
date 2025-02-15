<?php

namespace App\Http\Livewire;

use App\Models\Project;
use App\Models\RefSubmissionStatus;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectOverview extends Component
{
    use WithPagination;

    public $search;

    public $status;

    public $sort = 'id+asc';

    public $sortOptions = [
        'id+asc'        => 'ID A-Z',
        'id+desc'       => 'ID Z-A',
        'title+asc'     => 'Title A-Z',
        'title+desc'    => 'Title Z-A',
        'updated_at+asc'=> 'Last Updated A-Z',
        'updated_at+desc'=> 'Last Updated Z-A',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'status' => ['except' => ''],
        'sort' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFilterSort()
    {
        $this->sort = 'id+asc';
        $this->search = null;
        $this->status = null;

    }

    public function unpinProject($id)
    {
        auth()->user()->pinned_projects()->detach(Project::findOrFail($id));

        session()->flash('success','Successfully removed project to pinned list');
    }

    public function pinProject($id)
    {
        $pinnedProjects = auth()->user()->pinned_projects;

        if (count($pinnedProjects) >= 6) {
            $removeId = $pinnedProjects->first()->id;

            auth()->user()->pinned_projects()->detach($removeId);
//            return back()
//                ->with('error', 'You can only pin ten PAPs at a time');
        }

        auth()->user()->pinned_projects()->attach(Project::findOrFail($id));

        session()->flash('success','Successfully added project to pinned list');
    }

    public function render()
    {
        $query = Project::query();

        if ($this->search) {
            $query->where('title','LIKE', '%'.$this->search.'%');
        }

        if ($this->status) {
            $query->where('submission_status_id', RefSubmissionStatus::findByName($this->status)->id);
        }

        if ($this->sort) {
            $sortArr = explode('+', $this->sort);
            $sortField = $sortArr[0];
            $sortDir = $sortArr[1];
            $query->orderBy($sortField, $sortDir);
        }

        $projects = $query->paginate();

        return view('livewire.project-overview', compact('projects'))
            ->with('submission_statuses', RefSubmissionStatus::all());
    }
}
