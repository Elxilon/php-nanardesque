<?php
require_once __DIR__ . "/../config.php";
require __PRJ__ . "/class/Autoloader.php";

session_start($GLOBALS['session_options']);

Autoloader::register();
use mdb\Movie;

$movie_id = (isset($_GET['id'])) ? htmlspecialchars($_GET['id']) : 1;
$mov = new Movie($movie_id);

if (isset($_POST['commEmail']) and isset($_POST['commPseudo']) and isset($_POST['commText'])) {
    $resp = $mov->checkComment([
        'movie_id' => $movie_id,
        'email' => trim($_POST['commEmail']),
        'pseudo' => trim($_POST['commPseudo']),
        'commentaire' => trim($_POST['commText'])
    ]);
    if (!isset($resp['error'])) $mov->addComment($resp['comment']);
}

ob_start();

$mov->generateMoviePage($resp ?? null);

$content = ob_get_clean();
Template::render($content, $mov->getMovie()[0]->titre);