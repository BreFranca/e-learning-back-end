<?php

namespace App\Http\Controllers\Api;

Use App\DataCourse;
Use App\User;
Use App\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DataCourseController extends Controller
{
    /**
     * Display all course registers of specific user
     *
     * @param int $user
     * @return \Illuminate\Http\Response
     */
    public function index($user)
    {
        $mycourses = Course::userCourse($user)
        ->where('progress','>', -1);

        if(!$mycourses){
            return response()->json(400);
            //400: Bad request. The standard option for requests that fail to pass validation.
        }else{
            foreach($mycourses as $mycourse){
                if(!Course::where('id', $mycourse->course_id)->exists()){
                    $mycourses->forget($mycourse);
                }
            }
            if(!$mycourses){
                return response()->json(400);
            }else{
                $mycourses->sortByDesc('id');
                return response()->json($mycourses,200);
                //200: OK. The standard success code and default option.
            }
        }
    }

    /**
     * Display specific course registers of specific user
     *
     * @param int $user
     * @param int $course
     * @return \Illuminate\Http\Response
     */
    public function show($user,$course)
    {
        $course = Course::findOrFail($course);
        $mycourse = Course::userCourse($user)
        ->where('course_id','=',$course->id)
        ->first();

        return response()->json($mycourse,200);
        //200: OK. The standard success code and default option.
    }

    /**
     * Create new course register of specific user.
     *
     * @param int $user
     * @param int $course
     * @return \Illuminate\Http\Response
     */
    public function store($user,$course)
    {
        try{
            $user = User::findOrFail($user);
            $course = Course::findOrFail($course);
            DataCourse::updateOrCreate([
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],[
                'updated_at' => now(),
            ]);
            DataCourse::createDataLesson($user,$course);
        }catch(ModelNotFoundException $e){
            return response()->json(400);
            //400: Bad request. The standard option for requests that fail to pass validation.
        }
        return response()->json(201);
        //201: Object created. Useful for the store actions.
    }


    /**
     * Update specifict course register of specific user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $user
     * @param int $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$user,$course)
    {
        try{
            $course = Course::findOrFail($course);
            DataCourse::where('data_courses.user_id','=',$user)
                      ->where('data_courses.course_id','=',$course->id)
                      ->update($request->except('_method'));
        }catch(ModelNotFoundException $e){
            return response()->json(400);
            //400: Bad request. The standard option for requests that fail to pass validation.
        }
        return response()->json(204);
        //204: No content. When an action was executed successfully, but there is no content to return.
    }

    /**
     * do nothing
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json(405);
    }
}
