<?php

Route::group(['prefix' => 'api', 'middleware' => ['auth:api']], function () {
    Route::resource('profiles', 'Heloufir\SecurityStarter\Http\Controllers\ProfileController')
        ->except(['create', 'edit']);
    Route::resource('roles', 'Heloufir\SecurityStarter\Http\Controllers\RoleController')
        ->except(['create', 'edit'])
        ->middleware(['roles:all,ROLE_ADMIN,ROLE_USER']);
});