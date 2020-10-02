<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudyWeekRequest;
use App\Http\Resources\StudyWeekResource;
use App\Model\StudyClass;
use App\Model\StudyDay;
use App\Model\StudyWeek;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudyWeekController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $study_weeks = StudyWeek::where('owner_id', '=', Auth::user()->id)
            ->with(['days'=> function($query) {
                $query->orderBy('created_at', 'asc');
            }])
            ->get();

        if ($study_weeks) {
            return StudyWeekResource::collection($study_weeks);
        }

        return new JsonResponse([]);
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
     */
    public function store(StudyWeekRequest $request)
    {
        $data = $request->all();
        $study_week = new StudyWeek();
        $study_week->fill($data);
        $study_week->owner_id = Auth::user()->id;
        $study_week->save();

        $weekDate = Carbon::createFromFormat('Y-m-d',  $study_week->created_at->format('Y-m-d'));
        for ($i = 0; $i < 7; $i++)
        {
            $study_day = new StudyDay();
            $study_day->fill([
                'created_at' => $i == 0 ? $weekDate->format('Y-m-d') :
                    $weekDate->addDays(1)->format('Y-m-d'),
                'study_week_id' => $study_week->id,
            ]);
            $study_day->save();
        }

    }

    /**
     * Display the specified resource.
     *
     */
    public function show(StudyWeek $study_week)
    {
        return new StudyWeekResource($study_week);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudyWeek $study_week)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(StudyWeekRequest $request, StudyWeek $study_week)
    {
        $study_week->fill($request->all());
        $study_week->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy(StudyWeek $study_week)
    {
        $study_week->delete();
    }

    public function getCurrentWeeks()
    {
        $now = Carbon::now();
        $currentWeekStart = $now->startOfWeek()->format('Y-m-d');
        $futureWeekEnd = $now->addWeek()->endOfWeek()->format('Y-m-d');

        $study_weeks = StudyWeek::where('owner_id', '=', Auth::user()->id)
            ->whereBetween('created_at', [$currentWeekStart,$futureWeekEnd])
            ->orderBy('created_at', 'asc')
            ->skip(0)
            ->take(2)
            ->get();

        if ($study_weeks && count($study_weeks) != 2) {
            $study_weeks = StudyWeek::where('owner_id', '=', Auth::user()->id)
                ->orderBy('created_at', 'desc')
                ->take(2)
                ->get();
        }

        return StudyWeekResource::collection($study_weeks);
    }

    public function massUpdate(Request $request) {
        $errors = [];
        $data = array_filter($request->all(), function ($value) {
            return !is_null($value) && $value !== '';
        });

        // validate
        if (count($data) > 0) {
            foreach ($data as $day_id => $classes) {
                // clean all old data
                $study_day = StudyDay::findOrFail($day_id);
                $study_day->classes() ? $study_day->classes()->delete() : null;

                foreach ($classes as $study_class) {
                    $error = self::storeClass($study_class);
                    $error ? $errors[] = ['day_id' => $study_day->id, 'error' => $error] : null;
                }
            }
        }

        // clear from empty items on success
        return new JsonResponse(array_filter($errors, function ($value) {
            return !is_null($value) && $value !== '' && $value !== false;
        }));
    }

    public static function storeClass(array $data) {
        $validator = Validator::make($data, [
            'start_time'       => 'required|string|min:1',
            'end_time'         => 'required|string|min:1',
            'group_id'         => 'required|integer',
            'study_day_id'     => 'required|integer',
            'study_subject_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return join(', ', $validator->errors()->all());
        }

        // create study class
        $study_class = new StudyClass();
        $study_class->fill([
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'group_id' => $data['group_id'],
            'study_day_id' => $data['study_day_id'],
            'study_subject_id' => $data['study_subject_id'],
        ]);
        return !$study_class->save();
    }
}
