<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Point;
use App\User;
use App\Course;
use App\DataCourse;
use Illuminate\Support\Facades\DB;

class Dashboard extends Model
{
    public static function leaderboard(){
        $users = User::All()->each(function($user){
            $user->setAttribute('points', Point::total($user->id));
            $user->toArray();
        });
        // $users = $users->sortByDesc('points');
        $users = $users->sortByDesc('points')->toArray();
        
        return $users;
    }

    public static function latestCourses($n){
        $data = collect();
        // $courses = Course::orderBy('id', 'desc')->take($n)->get();
        $courses = Course::orderBy('id', 'desc')->take($n)->get()->toArray();

        return $courses;
    }

    public static function latestUsers($n){
        // $users = User::orderBy('id', 'desc')->take($n)->get();
        $users = User::orderBy('id', 'desc')->take($n)->get()->toArray();

        return $users;
    }

    public static function instructorCourses($user){
        $courses = Course::where('user_id',$user)->orderBy('end_date')->get();
        
        return $courses;
    }

    //gera o rank de cursos de acordo com a quantidade em andamento
    public static function rankCourses(){
        $courses = Course::All()->each(function($course){
            $count = DB::table('data_courses')
                    ->where('course_id', $course->id)
                    ->where('progress','>',0)
                    ->count();
            $course->SetAttribute('executes', $count);
            $course->toArray();
        });

        $courses = $courses->sortByDesc('executes');
        return $courses;
    }

    //procura na tabela data_courses por cursos finalizados
    public static function totalCoursesFinish(){
        $courses = DB::table('data_courses')
                    ->whereNotNull('finish')
                    ->where('progress',100)
                    ->get();

        return $courses;
    }

    public static function notFinishCourses(){
        $courses = DB::table('data_courses')
                    ->whereBetween('progress', array(0,100))
                    ->get();
        return $courses;
    }

    public static function rankusers(){
        $users = User::All()->each(function($user){
            $count = DB::table('data_courses')
                    ->where('user_id',$user->id)
                    ->where('progress','>',0)
                    ->count();
            $user->SetAttribute('courses_count', $count);
        });
        
        $users = $users->sortByDesc('courses_count');
        return $users;
    }

    public static function rankPosition($user){
        $rank = User::All()->each(function($user){
            $user->setAttribute('points', Point::total($user->id));
        });

        $rank = $rank->sortByDesc('points')->toArray();
        $i = 1;
        foreach($rank as $r){
            if($r['id'] == $user){
                return $i;
            }
            $i++;
        }
    }
}
