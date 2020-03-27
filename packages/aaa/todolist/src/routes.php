<?php

Route::group(["namespace" => 'Aaa\Todolist'], function ($route) {
   $route->resource('/task', 'TaskController');
});
