<?php
require_once "config.php";
require __DIR__ . "/class/Autoloader.php";

session_start($GLOBALS['session_options']);

Autoloader::register();
use mdb\Diapo;
$diapo = new Diapo();

ob_start();

$diapo->getDiapo();

$content = ob_get_clean();
Template::render($content);