<?php

Route::get('api/beeasy/blog/recent/{slug}', 'BeEasy\BlogExtension\Controllers\PostsController@recent');
Route::get('api/beeasy/blog/related/{slug}', 'BeEasy\BlogExtension\Controllers\PostsController@related');
