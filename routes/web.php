<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

// route to show the login form
//Route::get('login', 'HomeController@index');
//
//// route to process the form
Route::post('login', 'Auth\LoginController@doLogin');

Route::get('/users/index', ['uses' =>'UsersController@index']);
Route::get('/users/create', ['uses' =>'UsersController@create']);
Route::post('users/adduseraction', ['uses' =>'UsersController@createUserAction']);
Route::get('users/editUser/{userID}', ['uses' =>'UsersController@editUser']);
Route::post('users/edituseraction', ['uses' =>'UsersController@editUserAction']);

Route::get('/home/index/{status}', ['uses' =>'HomeController@index']);
Route::get('/home/edit/{ticketID}', ['uses' =>'HomeController@edit']);
Route::post('/home/editaction/{ticketID}', ['uses'=>'HomeController@editaction']);
Route::get('/home/closeticket/{ticketID}', ['uses' =>'HomeController@closeticket']);
Route::post('/home/closeticketaction/{ticketID}', ['uses'=>'HomeController@closeticketaction']);
Route::post('/home/editmultipleaction', ['uses'=>'HomeController@editmultipleaction']);

Route::get('/home/ticketDetails/{ticketID}', ['uses' =>'HomeController@ticketDetails']);
Route::get('/emphome/ticketDetails/{ticketID}', ['uses' =>'EmpHomeController@ticketDetails']);

Route::get('emphome/auth/{wsusername}/{wspassword}/{username}/{name}/{date}', ['uses' =>'EmpHomeController@auth']);
Route::get('emphome/index/{segment}', ['uses' =>'EmpHomeController@index']);
Route::get('emphome/addticket', ['uses' =>'EmpHomeController@addTicket']);


Route::get('ajax/getBuildings/{region}', ['uses' =>'AjaxController@getBuildings']);

Route::post('emphome/addticketaction', ['uses' =>'EmpHomeController@addTicketAction']);
