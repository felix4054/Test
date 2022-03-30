<?php

use App\Services\Router;
use App\Services\Page;


if (isset($_SESSION["name"])) {
    Router::redirect('/profile');
    die();
}

?>

<?php
Page::part("header");
?>
<!-- action="/user/login" method="POST"   -->

<div class="container">
    <h2 class="mt-3">Авторизация</h2>
    <form class="mt-3" id="login_form" style="width: 500px;">
       
        <div class="form-group">
            <label>Логин</label>
            <input type="text" class="form-control" id="login" name="data[login]" placeholder="Введите ваш логин">
            <!-- <small id="pasAJAX" class="form-text text-muted"></small> -->
        </div>
        <div class="form-group">
            <label>Пароль</label>
            <input type="password" class="form-control"id="password" name="data[password]" placeholder="Введите ваш пароль" >
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="check" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Запомнить</label>
        </div>

        <div class="alert alert-danger  display-none" id="login-errors">
        </div>

        <p id="msg_error"></p>

        <button type="submit" class="mt-3 btn btn-block btn-primary" id="login-button">Войти</button>
    </form>
</div>

<?php
Page::part("footer");
?>
<!-- </body>

</html> -->