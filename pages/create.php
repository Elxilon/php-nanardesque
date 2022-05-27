<?php
require_once __DIR__ . "/../config.php";
require __PRJ__ . "/class/Autoloader.php";

session_start($GLOBALS['session_options']);
if (!isset($_SESSION['log'])) {
    header("Location: " . __RPRJ__ . "pages/login.php");
    exit();
}

Autoloader::register();
use mdb\Create;

$create = new Create();

// si c'est set alors on est en mode édition pour tout le reste de la page
if (isset($_GET['id'])) {
    $movie = $create->getMovie(htmlspecialchars($_GET['id']))[0];

    if (isset($_POST['delBtn']) && $_POST['delBtn'] == "del-btn")
        $create->deleteMovie($movie); // partie suppression
}

if (isset($_POST['titre']) and isset($_POST['dateSortie']) and isset($_POST['createText'])) {
    $img = (!empty($_FILES['img']) && $_FILES['img']['size'] != 0) ? $_FILES['img'] : null;
    $resp = $create->checkForm(
        (object) [
            'titre' => trim($_POST['titre']),
            'date_sortie' => trim($_POST['dateSortie']),
            'synopsis' => trim($_POST['createText'])
        ],
        $img
    );

    if (!isset($resp['error'])) {
        if (isset($movie)) $create->editMovie($resp['new_movie'], $movie, $img);
        else $create->addMovie($resp['new_movie'], $img);
    }
}

ob_start();

$create->generateCreateHTML($resp ?? null, $movie ?? null);

$content = ob_get_clean();
Template::render($content, (isset($movie)) ? "Édition du film" : "Ajouter un film");