<?php

namespace App\Http\Controllers;

use App\DataTables\ProjectsDataTable;
use App\DataTables\ReviewsDataTable;
use App\Http\Requests\ReviewStoreRequest;
use App\Models\CipType;
use App\Models\PipTypology;
use App\Models\Project;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ReviewsDataTable $dataTable)
    {
        return $dataTable->render('reviews.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $project = Project::where('uuid', $request->query('project'))->firstOrFail();

        $review = new Review();

        return view('reviews.create', [
            'review' => $review,
            'project' => $project,
            'pip_typologies' => PipTypology::all(),
            'cip_types' => CipType::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ReviewStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReviewStoreRequest $request)
    {
        $project = Project::findOrFail($request->project_id);

        $project->review()->updateOrCreate($request->except('_token','project'));

        return redirect()->route('reviews.index')->with('message', 'Successfully added review');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        return view('reviews.edit', [
           'review'         => $review,
           'cip_types'      => CipType::all(),
           'pip_typologies' => PipTypology::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ReviewStoreRequest $request
     * @param \App\Models\Review $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ReviewStoreRequest $request, Review $review): \Illuminate\Http\RedirectResponse
    {
        $review->update($request->all());

        return redirect()->route('reviews.index')->with('message', 'Successfully added review');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return response()->noContent();
    }
}