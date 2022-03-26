<?php

use App\Services\Router;
use App\Services\Page;

if (isset($_SESSION["name"])) {
    Router::redirect('/profile');
}

// if (AppController::existSession()) {
//     Router::redirect('/profile');
//     // return true;
// }
?>

<?php
Page::part("header");
?>
<!-- action="/user/register" method="POST" -->

<div class="container">
    <h2 class="mt-3">Регистрация</h2>
    <form class="mt-3" id="register_form" style="width: 500px;">
        <div class="alert alert-success  display-none" id="register-success">
        </div>
        <div class="form-group">
            <label>Логин</label>
            <input type="text" class="form-control" id="login" name="data[login]" placeholder="Введите ваш логин ">
            <!-- <small id="emailHelp" class="form-text text-muted">Мы никогда не будем делиться вашей электронной почтой с кем-либо еще.</small> -->
        </div>
        <div class="form-group">
            <label>Пароль</label>
            <input type="password" class="form-control" name="data[password]" placeholder="Введите ваш пароль">
        </div>
        <div class="form-group">
            <label>Подтверждение пароля</label>
            <input type="password" class="form-control" name="data[confirm_password]" placeholder="Подтвердите ваш пароль">
        </div>
        <div class="form-group">
            <label>Email </label>
            <input type="email" class="form-control" name="data[email]" placeholder="введите ваш email">
        </div>
        <div class="form-group">
            <label>Имя</label>
            <input type="text" class="form-control" name="data[name]" placeholder="Введите ваше имя ">
            <!-- <small id="emailHelp" class="form-text text-muted">Мы никогда не будем делиться вашей электронной почтой с кем-либо еще.</small> -->
        </div>

        <div class="alert alert-danger  display-none" id="register-errors">
        </div>

        <button type="submit" class="mt-3 btn btn-block btn-primary" id="register-button">Зарегистрироваться</button>
    </form>

</div>

<?php
Page::part("footer");
?>
<!-- </body>

</html> -->