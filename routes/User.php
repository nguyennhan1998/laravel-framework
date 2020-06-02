<?php
Route::get('/', "HomeController@index");
Route::get('/home', 'HomeController@index')->name('home');
//
//Route::get("/demo-routing","WebController@demoRouting");
Route::get("/category/{category:slug}","HomeController@category");
