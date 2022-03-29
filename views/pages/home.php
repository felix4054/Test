<?php

use App\Services\Page;

?>

<?php
Page::part("header");
?>

<div class="jumbotron">
    <h1 class="display-4">Manao PHP + Json БД для хранения паролей!</h1>
    <p class="lead">Это простой пример на PHP, CSS, HTML, JS, Composer, Ajax, Session, Cookie, validation.</p>
    <hr class="my-4">
    <p>Задание тестовое от компании Manao.</p>
    <p class="lead">
        <a class="btn btn-primary btn-lg" href="/login" role="button">Вперед</a>
    </p>
</div>

<?php
Page::part("footer");
?>

<!-- </body>

</html> -->