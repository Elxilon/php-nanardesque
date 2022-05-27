<?php
require_once __DIR__ . "/../config.php";
require __PRJ__ . "/class/Autoloader.php";

session_start($GLOBALS['session_options']);

Autoloader::register();
use mdb\Browse;

$logged = isset($_SESSION['log']);

$curr_page = (isset($_GET['page']) && ctype_digit(strval($_GET['page']))) ? htmlspecialchars($_GET['page']) : 1;
$browse = new Browse($curr_page, [
    "str" => (isset($_GET['searchString'])) ? htmlspecialchars($_GET['searchString']) : '',
    "sort" => (isset($_GET['searchSort'])) ? htmlspecialchars($_GET['searchSort']) : 'id',
    "order" => (isset($_GET['searchOrder'])) ? htmlspecialchars($_GET['searchOrder']) : ''
]);

ob_start();

$browse->getBrowse($logged);

$content = ob_get_clean();
Template::render($content, "Liste des films");