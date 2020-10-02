<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudySubjectResource;
use App\Model\StudySubject;
use Illuminate\Http\Request;

class StudySubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return StudySubjectResource::collection(StudySubject::get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $subject = new StudySubject();
        $subject->fill($data);
        $subject->save();
    }

    /**
     * Display the specified resource.
     *
     * @param StudySubject $studySubject
     * @return StudySubjectResource
     */
    public function show(StudySubject $studySubject)
    {
        return new StudySubjectResource($studySubject);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param StudySubject $studySubject
     */
    public function edit(StudySubject $studySubject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param StudySubject $studySubject
     */
    public function update(Request $request, StudySubject $studySubject)
    {
        $studySubject->fill($request->all());
        $studySubject->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param StudySubject $studySubject
     * @throws \Exception
     */
    public function destroy(StudySubject $studySubject)
    {
        $studySubject->delete();
    }
}
