<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Manao - Auth</title>
    <link rel="stylesheet" href="/vendor/components/bootstrap/css/bootstrap.min.css" />
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" href="/assets/css/main.css" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Manao PHP + Json</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="/">Главная</a>
                </li>
            </ul>
        </div>
        <div class="d-flex">
            <?php
            if (!isset($_SESSION["name"])) {
            ?>
                <a class="nav-link active" href="/login">Авторизация</a>
                <a class="nav-link active" href="/register">Регистрация</a>
            <?php
            } else {
            ?>
                <a class="nav-link active" href="/profile">Профиль</a>

                <form action="/user/logout" method="POST">
                    <button type="submit" class="btn btn-danger">Выйти</button>

                </form>
            <?php
            }
            ?>

        </div>

    </nav>