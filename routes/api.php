<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'password'],function() {
	Route::post('/email', 'Auth\ForgotPasswordController@getResetToken');
	Route::post('/reset', 'Auth\ResetPasswordController@reset');
});

Route::group(['prefix'=> 'auth'],function(){
    Route::post('/register','Auth\RegisterController@register');
    Route::post("/login",'Auth\LoginController@login');
    Route::post('/login/{social}/callback','Auth\LoginController@handleProviderCallback')->where('social','twitter|facebook|linkedin|google');
});

Route::middleware(['jwt_auth'])->group(function(){
   Route::get('/hello',function(){
       return "Cool dude";
   });
});


//user routes
Route::resource('users', 'Api\UserController');

//Author routes
Route::resource('authors', 'Api\AuthorsController');

//Point routes
Route::resource('points', 'Api\PointsController');

//DataPoints routes
Route::get('leaderboard', 'Api\UsersController@points');
Route::get('users/{user}/points', 'Api\DataPointController@user');
Route::get('users/{user}/courses/{course}/quiz/{quiz}/points', 'Api\DataPointController@final');
Route::post('users/{user}/courses/{course}/points', 'Api\DataPointController@course');
Route::post('users/{user}/courses/{course}/lessons/{lesson}/points', 'Api\DataPointController@lesson');
Route::post('users/{user}/courses/{course}/quiz/{quiz}/points', 'Api\DataPointController@quiz');
Route::get('users/{user}/courses/{course}/quiz/{quiz}/final', 'Api\DataPointController@final');

//Level routes
Route::post('levels', 'Api\LevelsController@makeLevels');

//Quiz routes
Route::resource('courses/{course}/quiz', 'Api\QuizController');
Route::get('courses/{course}/final', 'Api\QuizController@final');
Route::get('courses/{course}/active', 'Api\QuizController@quizFinalActive');
Route::get('courses/{course}/questions', 'Api\QuizController@questions');
Route::get('courses/{course}/questions/{question}', 'Api\QuizController@courseQuestions');
Route::put('courses/{course}/questions/{question}', 'Api\QuizController@updateQuestion');
Route::post('courses/{course}/final/activate', 'Api\QuizController@activateFinal');
Route::post('courses/{course}/final/desactivate', 'Api\QuizController@desactivateFinal');

//Questions routes
Route::resource('courses/{course}/quiz/{quiz}/questions', 'Api\QuestionsController');

//Answers routes
Route::post('courses/{course}/quiz/{quiz}/questions/{question}/answers/','Api\AnswersController@store');
Route::put('courses/{course}/quiz/{quiz}/questions/{question}/answers/{answer}','Api\AnswersController@update');
Route::delete('courses/{course}/quiz/{quiz}/questions/{question}/answers/{answer}','Api\AnswersController@destroy');

//Course routes
Route::resource('courses', 'Api\CoursesController');
Route::get('courses/{course}', 'Api\CoursesController@show');
Route::get('courses/{course}/authors', 'Api\AuthorsController@list');
Route::post('courses/{course}/authors', 'Api\AuthorsController@courseAuthor');
Route::post('courses/order', 'Api\CoursesController@order');

//Lesson routes
Route::resource('courses/{course}/lessons', 'Api\LessonController');
Route::get('courses/{course}/lessons', 'Api\LessonController@index');
Route::get('courses/{course}/lessons/{lesson}', 'Api\LessonController@show');
Route::get('courses/{course}/lessons/{lesson}/media','Api\LessonController@media');
Route::post('courses/{course}/lessons/order', 'Api\LessonController@order');

//Lesson Question route
Route::resource('courses/{course}/lessons/{lesson}/questions', 'Api\LessonQuestionsController');

//Lesson Answers routes
Route::post('courses/{course}/lessons/{lesson}/questions/{question}/answers/','Api\LessonAnswersController@store');
Route::put('courses/{course}/lessons/{lesson}/questions/{question}/answers/{answer}','Api\LessonAnswersController@update');
Route::delete('courses/{course}/lessons/{lesson}/questions/{question}/answers/{answer}','Api\LessonAnswersController@destroy');

//Upload File routes
Route::post('upload','Api\UploadController@upload');

//DataCourse routes
Route::get('users/{user}/courses', 'Api\DataCourseController@index');
Route::get('users/{user}/courses/{course}', 'Api\DataCourseController@show');
Route::post('users/{user}/courses/{course}', 'Api\DataCourseController@store');
Route::put('users/{user}/courses/{course}', 'Api\DataCourseController@update');

//DataLesson routes
Route::get('users/{user}/courses/{course}/lessons/', 'Api\DataLessonController@index');
Route::get('users/{user}/courses/{course}/lessons/{lesson}', 'Api\DataLessonController@show');
Route::post('users/{user}/courses/{course}/lessons/{lesson}', 'Api\DataLessonController@store');
Route::put('users/{user}/courses/{course}/lessons/{lesson}', 'Api\DataLessonController@update');

//Certification routes
Route::get('users/{user}/certification', 'Api\CertificationController@user');
Route::get('users/{user}/courses/{course}/certification', 'Api\CertificationController@show');
Route::post('users/{user}/courses/{course}/certification/', 'Api\CertificationController@store');
Route::resource('certification/templates', 'Api\TemplateController');

//Team routes
Route::resource('teams', 'Api\TeamController');
Route::post('teams/add', 'Api\TeamController@add');
Route::post('teams/remove', 'Api\TeamController@remove');

//Trail routes
Route::resource('trails', 'Api\TrailController');

//Dashboard routes
Route::get('dashboard/{id}', 'Api\DashboardController@dashboard');
Route::get('dashboard/admin/{id}', 'Api\DashboardController@admin');
Route::get('dashboard/instructor/{id}', 'Api\DashboardController@instructor');
Route::get('dashboard/student/{id}', 'Api\DashboardController@student');