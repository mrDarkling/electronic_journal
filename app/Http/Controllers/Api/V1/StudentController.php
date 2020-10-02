<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Model\Student;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index()
    {
        return StudentResource::collection(Student::get());
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
    public function store(StudentRequest $request)
    {
        $data = $request->all();
        $student = new Student();
        $student->fill($data);
        $student->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Student  $student
     * @return StudentResource
     */
    public function show(Student $student)
    {
        return new StudentResource($student);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(StudentRequest $request, Student $student)
    {
        $student->fill($request->all());
        $student->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Model\Student $student
     * @throws \Exception
     */
    public function destroy(Student $student)
    {
        $student->delete();
    }
}
