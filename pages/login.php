<?php
require_once __DIR__ . "/../config.php";
require __PRJ__ . "/class/Autoloader.php";

session_start($GLOBALS['session_options']);

Autoloader::register();
use mdb\Logger;

$logger = new Logger();

if (isset($_POST['username']) and isset($_POST['password'])) {
    $resp = $logger->checkLogs(trim($_POST['username']), trim($_POST['password']));
    if ($resp['granted']){
        $_SESSION['log'] = $resp['granted'];
        header("Location: ". __RPRJ__ . "pages/browse.php");
        exit() ;
    }
}

ob_start();

if (isset($resp)) $logger->generateLoginForm($resp['username'], $resp['error']);
else $logger->generateLoginForm();

$content = ob_get_clean();
Template::render($content, "Connectez-vous");
