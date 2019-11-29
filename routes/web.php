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

Route::get('/', 'HomeController@index')->name('home');

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::resource('projects', 'ProjectsController');
Route::resource('keywords', 'KeywordsController');
Route::get('set_keyword/{id}', array('uses' => 'KeywordsController@set_keyword', 'as' => 'set_keyword'));
Route::post('save_keyword/{id}', array('uses' => 'KeywordsController@save_keyword', 'as' => 'save_keyword'));



Route::post('/search/engine', 'ProjectsController@search_engine')->name('search.engine');
Route::post('/search/language', 'ProjectsController@search_language')->name('search.language');
Route::post('/search/location', 'ProjectsController@search_location')->name('search.location');

Route::get('view_projects', array('uses' => 'ProjectsController@index', 'as' => 'view_projects'));
Route::get('set_project', array('uses' => 'ProjectsController@set_project', 'as' => 'set_project'));
Route::post('save_project', array('uses' => 'ProjectsController@save_project', 'as' => 'save_project'));
/*Route::get('view_projects_results', array('uses' => 'ProjectsController@view_projects_results', 'as' => 'view_projects_results'));
*/

/*Route::resource('keywords', 'KeywordsController');*/



Route::get('/tasker' , function() {
    Artisan::call('tasker:cron');
});

Route::get('/retriever' , function() {
    Artisan::call('retriever:cron');
});
