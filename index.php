
<?php 
session_start();


require_once __DIR__  . "/vendor/autoload.php";
define('ROOT', dirname(__FILE__));
const CONFIG = ROOT. '/config';
require_once __DIR__  . "/router/routes.php";


