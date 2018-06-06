<?php
Route::pattern('id', '[0-9]+');
Route::pattern('alias', '[0-9a-z_-]+');

Route::group([
    'prefix' => config('reactiveadmin.uri'),
    'namespace' => 'Karellens\\ReactiveAdmin\\Controllers',
    'middleware' => ['web', 'auth', 'raa_menu']
], function() {

//    Route::get('/lang/{id}', function($id)
//    {
//        session('raa_locale', config('reactiveadmin.locales')[$id]);
//        App::setLocale(config('reactiveadmin.locales')[$id]);
//        return back();
//    });

//    Route::post('/{alias}/upload',          'ReactiveAdminController@upload');
//    Route::put('/{alias}/{id?}/upload',     'ReactiveAdminController@upload');
//    Route::delete('/upload',                'ReactiveAdminController@upload');

    Route::get('/{alias?}',                 'ReactiveAdminController@index');   //  model ? model : dashboard
//    Route::get('/{alias}/create',           'ReactiveAdminController@create');
//    Route::get('/{alias}/{id}',             'ReactiveAdminController@show');
//    Route::get('/{alias}/{id}/edit',        'ReactiveAdminController@edit');
//
//    Route::get('/{alias}/{id}/trash',       'ReactiveAdminController@trash');
//    Route::get('/{alias}/{id}/restore',     'ReactiveAdminController@restore');
//
//    Route::post('/{alias}',                 'ReactiveAdminController@store');
//    Route::put('/{alias}/{id}',             'ReactiveAdminController@update');
//    Route::delete('/{alias}/{id}/destroy',  'ReactiveAdminController@destroy');
});