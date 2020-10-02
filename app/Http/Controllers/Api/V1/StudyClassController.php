<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudyClassResource;
use App\Model\StudyClass;
use Illuminate\Http\Request;

class StudyClassController extends Controller
{
    /**
     * Display a listing of the resource.
     **/
    public function index()
    {
        return StudyClassResource::collection(StudyClass::get());
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
        $study_class = new StudyClass();
        $study_class->fill($data);
        $study_class->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $study_class
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudyClass $study_class)
    {
        $study_class->fill($request->all());
        $study_class->save();
    }


    public function destroy(StudyClass $study_class)
    {
        $study_class->delete();
    }
}
