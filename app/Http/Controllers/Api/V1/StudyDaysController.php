<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudyDayResource;
use App\Model\StudyDay;
use Illuminate\Http\Request;

class StudyDaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return StudyDayResource::collection(StudyDay::get());
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $study_day = new StudyDay();
        $study_day->fill($data);
        $study_day->save();
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(StudyDay $study_day)
    {
        return new StudyDayResource($study_day);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit(StudyDay $study_day)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, StudyDay $study_day)
    {
        $study_day->fill($request->all());
        $study_day->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy(StudyDay $study_day)
    {
        $study_day->delete();
    }
}
