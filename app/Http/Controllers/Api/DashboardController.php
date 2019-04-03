<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Dashboard;
use App\User;
use App\Course;
use App\DataCourse;
use Illuminate\Support\Facades\DB;
use App\Point;

class DashboardController extends Controller
{
    public function dashboard($id){
        $user = User::findOrFail($id);

        $courses = DB::table('data_courses')
                    ->leftJoin('courses','data_courses.course_id','=','courses.id')
                    ->where('data_courses.user_id', $user->id)
                    ->get();

        $certifications= DB::table('certifications')
                            ->where('user_id', $user->id)
                            ->get();

        $points = Point::total($user->id);

        $position = Dashboard::rankPosition($user->id);
        
        //admin
        if($user->role_id == 1){
            $leaderboard = Dashboard::leaderboard();
            $latest_courses = Dashboard::latestCourses(5);
            $latest_users = Dashboard::latestUsers(5);
            $total_courses = Course::count();
            $total_users = User::count();
            $never_execute_user = Dashboard::rankusers()->where('courses_count',0)->count();
            $rank_courses = Dashboard::rankCourses();
            $finish_courses = Dashboard::totalCoursesFinish()->count();
            $never_execute = $rank_courses->where('executes', 0)->count();
            $not_finish = Dashboard::notFinishCourses()->count();

            return response()->json([
                'id' => $user->id,
                'courses' => $courses->toArray(),
                'started' => $courses->where('progress','>',0)->toArray(),
                'finished' => $courses->where('progress',100)->toArray(),
                'certifications' => $certifications->toArray(),
                'level' => $user->level,
                'points' => $points,
                'position' => $position,
                'user_data' => [
                    'ranking' => $leaderboard,
                    'latest_users' => $latest_users,
                    'total_users' => $total_users,
                    'never_execute' => $never_execute_user,
                ],
                'course_data' => [
                    'total_courses' => $total_courses,
                    'latest_courses' => $latest_courses,
                    'rank_courses' => $rank_courses,
                    'finish'=> $finish_courses,
                    'not_finish' => $not_finish,
                    'never_execute' => $never_execute,
                ],
            ],200);

        //instructor
        }else if($user->role_id == 2){
            
            $mycourses = Dashboard::instructorCourses($user->id);

            $started = 0;
            $onGoing = 0;
            $finished = 0;
            foreach($mycourses as $course){
                $course->setAttribute('started', DataCourse::started($course->id));
                $course->setAttribute('onGoing', DataCourse::onGoing($course->id));
                $course->setAttribute('finished', DataCourse::finished($course->id));
                
                $started += $course->started;
                $onGoing += $course->onGoing;
                $finished += $course->finished;
            }

            return response()->json([
                'id' => $user->id,
                'courses' => $courses,
                'started' => $courses->where('progress','>',0),
                'finished' => $courses->where('progress',100),
                'certifications' => $certifications,
                'level' => $user->level,
                'points' => $points,
                'position' => $position,
                'course_data' => [
                    'mycourses' => $mycourses,
                    'total' => $mycourses->count(),
                    'started' => $started,
                    'onGoing' => $onGoing,
                    'finished' => $finished,
                ],
            ],200);

        //student
        }else{

            return response()->json([
                'id' => $user->id,
                'courses' => $courses,
                'started' => $courses->where('progress','>',0),
                'finished' => $courses->where('progress',100),
                'certifications' => $certifications,
                'level' => $user->level,
                'points' => $points,
                'position' => $position,
            ], 200);
        }
    }

    public function admin($id){
        $user = User::findOrFail($id);
        $courses = DB::table('data_courses')
                    ->leftJoin('courses','data_courses.course_id','=','courses.id')
                    ->where('data_courses.user_id', $user->id)
                    ->get();
        $leaderboard = Dashboard::leaderboard();
        $latest_courses = Dashboard::latestCourses(5);
        $users = Dashboard::latestUsers(5);
        $totalCourses = Course::count();
        $totalUsers = User::count();
        $points = Point::total($user->id);
        $position = Dashboard::rankPosition($user->id);
        $leaderboard = Dashboard::leaderboard();

        $data = array(
            'leaderboard'=> $leaderboard,
            'level' => $user->level,
            'points' => $points,
            'position' => $position,
            'courses' => $courses,
            'latest_courses' => $latest_courses,
            'latest_users' => $users,
            'totalCourses' => $totalCourses,
            'totalUsers' => $totalUsers
        );
        return response()->json($data, 200);
    }

    public function instructor($id){
        $user = User::findOrFail($id);
        $courses = Dashboard::instructorCourses($user->id);
        $leaderboard = Dashboard::leaderboard();
        $points = Point::total($user->id);
        $position = Dashboard::rankPosition($user->id);
        $leaderboard = Dashboard::leaderboard();

        $started = 0;
        $onGoing = 0;
        $finished = 0;
        foreach($courses as $course){
            $course->setAttribute('started', DataCourse::started($course->id));
            $course->setAttribute('onGoing', DataCourse::onGoing($course->id));
            $course->setAttribute('finished', DataCourse::finished($course->id));
            
            $started += $course->started;
            $onGoing += $course->onGoing;
            $finished += $course->finished;
        }
        // lista de usuarios*
        $data = array(
            'leaderboard'=> $leaderboard,
            'level' => $user->level,
            'points' => $points,
            'position' => $position,
            'courses' => $courses,
            'started' => $started,
            'onGoing' => $onGoing,
            'finished' => $finished,
        );
        return response()->json($data, 200);

    }

    public function student($id){
        $user = User::findOrFail($id);

        $courses = DB::table('data_courses')
                    ->leftJoin('courses','data_courses.course_id','=','courses.id')
                    ->where('data_courses.user_id', $user->id)
                    ->get()->toArray();

        $certifications= DB::table('certifications')
                            ->where('user_id', $user->id)
                            ->get()->toArray();


        $points = Point::total($user->id);
        $position = Dashboard::rankPosition($user->id);
        $leaderboard = Dashboard::leaderboard();

        $data = array(
            'leaderboard'=> $leaderboard,
            'courses' => $courses,
            'certifications' => $certifications,
            'level' => $user->level,
            'points' => $points,
            'position' => $position,
        );
        return response()->json($data, 200);
    }
}
