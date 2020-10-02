<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentsGroupRequest;
use App\Http\Resources\StudentGroupResource;
use App\Model\StudentsGroup;

class StudentsGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *

     */
    public function index()
    {
        return StudentGroupResource::collection(StudentsGroup::get());
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
    public function store(StudentsGroupRequest $request)
    {
        $data = $request->all();
        $group = new StudentsGroup();
        $group->fill($data);
        $group->save();
    }

    /**
     * Display the specified resource.
     *
     * @param StudentsGroup $studentsGroup
     * @return StudentGroupResource
     */
    public function show(StudentsGroup $studentsGroup)
    {
        return new StudentGroupResource($studentsGroup);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param StudentsGroup $studentsGroup
     * @return void
     */
    public function edit(StudentsGroup $studentsGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StudentsGroupRequest $request
     * @param StudentsGroup $studentsGroup
     * @return \Illuminate\Http\Response
     */
    public function update(StudentsGroupRequest $request, StudentsGroup $studentsGroup)
    {

        $studentsGroup->fill($request->all());
        $studentsGroup->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param StudentsGroup $studentsGroup
     * @return void
     * @throws \Exception
     */
    public function destroy(StudentsGroup $studentsGroup)
    {
        $studentsGroup->delete();
    }
}
