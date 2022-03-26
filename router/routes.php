<?php

use App\Services\Router;
use App\Controllers\UserController;


Router::page('/', 'home');
Router::page('/login', 'login');
Router::page('/register', 'register');
Router::page('/profile','profile');


Router::action('/user/login', UserController::class, 'login', true);
Router::action('/user/register', UserController::class, 'register', true);
Router::action('/user/logout', UserController::class, 'logout');

Router::run();