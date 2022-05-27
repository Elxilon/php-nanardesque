<?php
namespace mdb;

class Create
{
    private MoviesDB $mdb;

    public function __construct() { $this->mdb = new MoviesDB(); }

    /**
     * Retourne le film correspondant à l'id passée en entrée
     *
     * @param int $id id du film
     * @return array
     */
    public function getMovie(int $id): array {
        return $this->mdb->exec(
            "SELECT * FROM film WHERE id = :id",
            ['id' => $id],
            "\mdb\MovieRenderer"
        );
    }

    /**
     * Fait appel à la méthode deleteMovie() de la classe MoviesDB pour supprimer les commentaires
     * du film ainsi que le film puis on redirige l'utilisateur sur la page de la liste des films
     *
     * @param object $movie le film à supprimer
     * @return void
     */
    public function deleteMovie(object $movie): void {
        $this->mdb->deleteMovie($movie);
        header("Location: " . __RPRJ__ . "pages/browse.php");
        exit();
    }

    /**
     * Vérifie si le film que l'utilisateur souhaite poster, correspond aux critères imposés
     *
     * @param object $movie
     * @param array|null $img
     * @return array Tableau qui retourne un objet correspondant au nouveau film si les paramètres sont validés,
     *               ainsi qu'un message d'erreur s'il y a sinon null il sera
     */
    public function checkForm(object $movie, array $img=null): array {
        $error = null;

        if (empty($movie->titre)) $error .= "Le titre ne peut pas être vide !";

        if (preg_match('/^\d{4}$', $movie->date_sortie)) {
            if (isset($error)) $error .= "<br>";
            $error .= "L'année saisie n'est pas valide !";
        }

        if (empty($movie->synopsis)) {
            if (isset($error)) $error .= "<br>";
            $error .= "Le synopsis ne peut pas être vide !";
        } else if (str_word_count($movie->synopsis) >= 100) {
            if (isset($error)) $error .= "<br>";
            $error .= "Le commentaire ne doit pas dépasser 100 mots !";
        }

        if (isset($img)) {
            if (empty($img) || $img['size'] == 0) {
                if (isset($error)) $error .= "<br>";
                $error .= "Aucun fichier uploadé !";
            } else {
                $file_type = exif_imagetype($img['tmp_name']);
                if ($img['error'] != 0) {
                    if (isset($error)) $error .= "<br>";
                    $error .= "Erreur d'upload du fichier (code : " . $img['error'] . ").";
                } else if ($file_type != IMAGETYPE_GIF && $file_type != IMAGETYPE_JPEG && $file_type != IMAGETYPE_PNG) {
                    if (isset($error)) $error .= "<br>";
                    $error .= "Le fichier uploadé n'est pas une image !";
                }
            }
        }

        return [
            'new_movie' => $movie,
            'error' => $error
        ];
    }

    /**
     * Ajoute un film à la BDD
     *
     * @param object $movie Objet avec les informations du film à ajouter
     * @param array $img Tableau comportant les informations de l'image uploadée
     * @return void
     */
    public function addMovie(object $movie, array $img): void {
        move_uploaded_file($img['tmp_name'], __IMG__ . $img['name']);
        $params = [
            'titre' => $movie->titre,
            'date_sortie' => $movie->date_sortie,
            'img' => $img['name'],
            'synopsis' => $movie->synopsis
        ];
        $this->mdb->exec(
            "INSERT INTO film (titre, date_sortie, img, synopsis) VALUES (:titre, :date_sortie, :img, :synopsis)",
            $params
        );
    }

    /**
     * Modifie un film déjà présent dans la BDD
     *
     * @param object $new_movie Objet comportant les informations actualisées du film
     * @param object $movie Objet correspondant au film avant modification avec toutes ses infos
     * @param array|null $img Tableau comportant les informations de l'image uploadée
     * @return void
     */
    public function editMovie(object $new_movie, object $movie, array $img=null): void {
        if (isset($img)) {
            move_uploaded_file($img['tmp_name'], __IMG__ . $img['name']);
            if (file_exists(__IMG__ . $movie->img)) unlink(__IMG__ . $movie->img);
            $final_img = $img['name'];
        } else $final_img = $movie->img;

        $params = [
            'titre' => $new_movie->titre,
            'date' => $new_movie->date_sortie,
            'img' => $final_img,
            'synopsis' => $new_movie->synopsis,
            'id' => $movie->id
        ];
        $this->mdb->exec(
            "UPDATE film SET titre = :titre, date_sortie = :date, img = :img, synopsis = :synopsis WHERE id = :id",
            $params
        );
    }

    /**
     * Ajoute un message affichant si le film a bien été ajouté ou modifié
     *
     * @param string $titre Titre du film
     * @param bool $edit Booléen, true si on est en édition false si on ajoute un film
     * @return void
     */
    public function getSuccess(string $titre, bool $edit): void {?>
        <div id="success">
            <span><?= $titre ?> a bien été <?= ($edit) ? "modifié" : "ajouté" ?> !</span>
            <button id="close-success" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                    <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
                </svg>
            </button>
        </div>
        <?php
    }

    /**
     * Génère le formulaire pour ajouter ou éditer un film
     *
     * @param object|null $movie Objet contenant les infos du film correspondant sinon rien
     * @param bool $edit Booléen, true si on est en édition false si on ajoute un film
     * @param string|null $message Message d'erreur si le film n'est pas conforme sinon rien
     * @return void
     */
    public function generateCreateForm(object $movie=null, bool $edit=false, string $message=null): void {?>
        <form method="post" id="create-form" enctype="multipart/form-data">
            <legend><?= ($edit) ? "Édition du film" : "Ajouter un film" ?></legend>
            <div id="form-inputs">
                <div id="error" class="error">
                    <?php if (isset($message)): ?>
                        <div><?php echo $message ?></div>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="titre" class="visually-hidden">Titre</label>
                    <input id="titre" type="text" class="form-control" name="titre" placeholder="Titre"
                           value="<?php if (isset($movie->titre)) echo $movie->titre ?>" autofocus>
                </div>
                <div>
                    <label for="date-sortie" class="visually-hidden">Année de sortie</label>
                    <input id="date-sortie" type="text" class="form-control" name="dateSortie" placeholder="Année de sortie"
                           value="<?php if (isset($movie->date_sortie)) echo $movie->date_sortie ?>">
                </div>
                <div>
                    <label for="img" class="visually-hidden">Upload</label>
                    <input id="img" type="file" class="form-control" name="img">
                </div>
                <div>
                    <label for="createText">Synopsis</label>
                    <textarea class="form-control" id="createText" name="createText" rows="4"><?php if (isset($movie->synopsis)) echo $movie->synopsis ?></textarea>
                </div>
            </div>
            <div id="btn-container">
                <?php if (!$edit): ?>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                <?php else: ?>
                    <button type="submit" class="btn btn-primary">Éditer</button>
                    <input type="hidden" name="delBtn" value="">
                    <button type="submit" id="del-btn" class="btn btn-danger">Supprimer</button>
                <?php endif; ?>
                <button type="reset" class="btn btn-outline-danger">Reset</button>
            </div>
        </form>
        <?php
    }

    /**
     * Génère la preview du film
     *
     * @param string|null $img Lien vers l'image à afficher si défini
     * @return void
     */
    public function generateCreateCard(string $img=null): void {?>
        <div id="card-preview" class="card d-none d-md-block">
            <?php if (isset($img)): ?>
                <img id="preview-img" src="<?= __RIMG__ . $img; ?>">
            <?php else: ?>
                <img id="preview-img" class="visually-hidden" src="">
            <?php endif; ?>
            <div id="preview-infos" class="card-img-overlay text-white">
                <div>
                    <h4 id="preview-title">Titre...</h4>
                    <div id="preview-date">Date de sortie...</div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Génère les élements de la page, en fonction de si on est en mode édition ou ajout de film notamment
     *
     * @param array|null $resp la réponse obtenu de la méthode checkForm()
     * @param object|null $movie si en édition, le film avant modification
     * @return void
     */
    public function generateCreateHTML(array $resp=null, object $movie=null): void {?>
        <div id="create-container">
            <?php if (isset($resp) && !isset($resp['error']))
                $this->getSuccess($resp['new_movie']->titre, isset($movie));?>
            <div id="create-content">
                <?php
                $this->generateCreateForm($resp['new_movie'] ?? $movie, isset($movie), $resp['error']);

                if (isset($movie)) {
                    if ($_FILES['img']['size'] != 0) $this->generateCreateCard($_FILES['img']['name']);
                    else $this->generateCreateCard($movie->img);
                }
                else $this->generateCreateCard();?>
            </div>
        </div>
        <script src="<?= __RPRJ__ ?>js/create.js"></script>
        <?php
    }
}