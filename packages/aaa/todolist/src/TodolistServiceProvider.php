<?php

namespace Aaa\Todolist;

use Illuminate\Support\ServiceProvider;

class TodolistServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->loadViewsFrom(__DIR__ . '/views', 'todolist');
        $this->publishes([
            __DIR__ . '/views' => base_path('resources/views/aaa/todolist'),
        ]);
    }

    public function register()
    {
        $this->app->make('Aaa\Todolist\TaskController');
    }
}
