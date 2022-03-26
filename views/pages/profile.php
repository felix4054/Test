<?php

use App\Services\Router;
use App\Services\Page;

if (!isset($_SESSION["name"])) {
    Router::redirect('/login');
}

?>

<?php
Page::part("header");
?>

<div class="container">
    <form action="/user/logout" method="POST">
        <div class="jumbotron">
            <h1 class="display-4">Hello, <?= htmlspecialchars($_SESSION['name']); ?>.</h1>
            <!-- <p class="lead">
                <button type="submit" class="mt-4 btn btn-primary">Выйти</button>
                
            </p> -->
        </div>

    </form>
</div>
</body>

</html>