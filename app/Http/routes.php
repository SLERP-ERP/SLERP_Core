<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


error_reporting(E_ALL);
ini_set('display_errors', 1);

Route::get('/', function () {
    return Redirect::away(url('docs'));
});

Route::group(['prefix' => 'api'], function () {

    Route::get('/', function () {
        return Redirect::away(url('docs'));
    });

    Route::group(['prefix' => 'v1'], function () {

        Route::get('/', function () {
            return Redirect::away(url('docs'));
        });

        Route::resource('campuses', 'CampusController');
        Route::get('campuses/code/{code}', 'CampusController@showByCode');
        Route::get('campuses/{id}/building', 'BuildingController@campusBuildings');
        Route::get('campuses/{id}/room', 'RoomController@campusRooms');
        Route::get('campuses/{id}/user', 'UserController@campusUsers');
        Route::delete('campuses/code/{code}', 'CampusController@destroyByCode');

        Route::resource('buildings', 'BuildingController');
        Route::get('buildings/code/{code}', 'BuildingController@showByCode');
        Route::get('buildings/{id}/room', 'RoomController@buildingRooms');
        Route::get('buildings/{id}/user', 'UserController@buildingUsers');
        Route::delete('buildings/code/{code}', 'BuildingController@destroyByCode');

        Route::resource('rooms', 'RoomController');

        Route::resource('users', 'UserController');
        Route::get('users/{id}/room', 'RoomController@userRooms');
        Route::get('users/{id}/role', 'RoleController@userRoles');
        Route::get('users/{id}/email', 'EmailController@userEmails');
        Route::get('users/{id}/phone', 'PhoneController@userPhones');
        Route::get('users/{id}/course', 'CourseController@userCourses');
        Route::get('users/user_id/{user_id}', 'UserController@showByUserId');
        Route::get('users/user_id/{user_id}/room', 'RoomController@userRoomsByUserId');
        Route::get('users/user_id/{user_id}/role', 'RoleController@userRolesByUserId');
        Route::get('users/user_id/{user_id}/email', 'EmailController@userEmailsByUserId');
        Route::get('users/user_id/{user_id}/phone', 'PhoneController@userPhonesByUserId');
        Route::get('users/user_id/{user_id}/course', 'CourseController@userCoursesByUserId');
        Route::get('users/username/{username}', 'UserController@showByUsername');
        Route::get('users/username/{username}/room', 'RoomController@userRoomsByUsername');
        Route::get('users/username/{username}/role', 'RoleController@userRolesByUsername');
        Route::get('users/username/{username}/email', 'EmailController@userEmailsByUsername');
        Route::get('users/username/{username}/phone', 'PhoneController@userPhonesByUsername');
        Route::get('users/username/{username}/course', 'CourseController@userCoursesUsername');
        Route::delete('users/user_id/{user_id}', 'UserController@destroyByUserId');
        Route::delete('users/username/{username}', 'UserController@destroyByUsername');

        Route::resource('roles', 'RoleController');
        Route::get('roles/{id}/user', 'UserController@roleUsers');
        Route::get('roles/code/{code}', 'RoleController@showByCode');
        Route::delete('roles/code/{code}', 'RoleController@destroyByCode');

        Route::resource('emails', 'EmailController');

        Route::resource('phones', 'PhoneController');

        Route::resource('departments', 'DepartmentController');
        Route::get('departments/code/{code}', 'DepartmentController@showByCode');
        Route::get('departments/{id}/course', 'CourseController@departmentCourses');
        Route::get('departments/code/{code}/course', 'CourseController@departmentCoursesByCode');
        Route::delete('departments/code/{code}', 'DepartmentController@destroyByCode');

        Route::resource('courses', 'CourseController');
        Route::get('courses/{id}/user', 'UserController@courseUsers');
        Route::get('courses/{id}/department', 'DepartmentController@courseDepartment');
        Route::get('courses/code/{code}', 'CourseController@showByCode');
        Route::get('courses/code/{code}/user', 'UserController@courseUsersByCode');
        Route::get('courses/code/{code}/department', 'DepartmentController@courseDepartmentByCode');
        Route::delete('courses/code/{code}', 'CourseController@destroyByCode');

       /* Route::resource('communities', 'CommunityController');
        Route::get('communities/{id}/user', 'CommunityController@communityUsers');
        Route::get('communities/code/{code}', 'CommunityController@showByCode');
        Route::delete('communities/code/{code}', 'CommunityController@destroyByCode');*/


    });
});