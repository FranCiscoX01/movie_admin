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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function() {
    /**
     * Subir imagen
     */
    Route::post('picture/upload', 'PictureController@uploadPictureFilm');
    Route::post('picture/remove', 'PictureController@removePictureFilm');
    /**
     * Peliculas
     */
    Route::get('films', 'FilmController@index')->name('films');
    Route::get('films/get-data', 'FilmController@getFilms');
    Route::get('films/create', 'FilmController@create');
    Route::post('films/store', 'FilmController@store');
    Route::put('films/{id}/change-status', 'FilmController@changeStatus');
    Route::get('films/{id}/get-all-info', 'FilmController@getAllInfo');
    Route::post('films/update', 'FilmController@update');
    Route::post('film/upload/excel', 'FilmController@uploadFilmExcel');
    Route::post('film/remove/excel', 'FilmController@removeFilmExcel');
    Route::get('films/upload-data-excel', 'FilmController@uploadDataExcel');
    /**
     * Categoria de Peliculas
     */
    Route::get('category', 'CategoryMovieController@index')->name('category');
    Route::get('category/get-data', 'CategoryMovieController@getCategories');
    Route::post('category/store', 'CategoryMovieController@store');
    Route::put('category/{id}/change-status', 'CategoryMovieController@changeStatus');
    Route::put('category/{id}/update', 'CategoryMovieController@update');
});
