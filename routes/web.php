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

// Route::get('/', function () {
//     return view('welcome');

// });

//UPLOAD DATA TO LIVE
Route::post('/upload_json/upload_new_member', 'ArczoneController@upload_new_member');
Route::post('/upload_json/upload_new_dtr','ArczoneController@upload_new_dtr');
Route::post('/upload_json/upload_new_pos','ArczoneController@upload_new_pos');
Route::post('/upload_json/upload_new_customer_balance','ArczoneController@upload_new_customer_balance');

//UPLOAD LOCAL USING LIVE DATABASE
Route::post('/get_json/upload_new_pos_to_local_dtr','ArczoneController@upload_to_local_daily_time_records');

Route::post('/insert_local_to_dummy_tbl_compare','ArczoneController@insert_local_to_dummy_tbl_compare');
