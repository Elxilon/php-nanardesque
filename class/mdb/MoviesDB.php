<?php

namespace mdb;
use \pdo_wrapper\PdoWrapper;
include __PRJ__ . 'DB_CREDENTIALS.php';

class MoviesDB extends PdoWrapper
{

    public function __construct() {
        parent::__construct(
            $GLOBALS['db_name'],
            $GLOBALS['db_host'],
            $GLOBALS['db_port'],
            $GLOBALS['db_user'],
            $GLOBALS['db_pwd']
        );
    }

    /**
     * Ajoute le commentaire de l'utilisateur à la BDD
     *
     * @param array $params Tableau des éléments du formulaire qu'à rempli l'utilisateur
     * @return void
     */
    public function addComment(array $params): void {
        $this->exec(
            "INSERT INTO comment (movie_id, email, pseudo, commentaire) VALUES (:movie_id, :email, :pseudo, :commentaire)",
            $params
        );
    }

    /**
     * Supprime un film de la BDD
     *
     * @param object $movie Objet correspondant au film avec toutes ses infos
     * @return void
     */
    public function deleteMovie(object $movie): void {
        if (file_exists(__IMG__ . $movie->img)) unlink(__IMG__ . $movie->img);
        $params = ['id' => $movie->id];

        $this->exec(
            "DELETE FROM comment WHERE movie_id = :id",
            $params
        );
        $this->exec(
            "DELETE FROM film WHERE id = :id",
            $params
        );
    }
}