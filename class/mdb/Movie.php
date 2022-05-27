<?php
namespace mdb;

class Movie
{
    private MoviesDB $mdb;
    private array $movie, $comments;

    public function __construct(int $id) {
        $this->mdb = new MoviesDB();
        $this->movie = $this->mdb->exec(
            "SELECT * FROM film WHERE id = :id",
            ['id' => $id],
            "\mdb\MovieRenderer"
        );
        $this->comments = $this->mdb->exec(
            "SELECT * FROM comment WHERE movie_id = :mid",
            ['mid' => $id],
            "\mdb\CommentRenderer"
        );
    }

    public function getMovie(): array {
        return $this->movie;
    }

    public function getComments(): array {
        return $this->comments;
    }

    /**
     * Vérifie si le commentaire que l'utilisateur souhaite poster, correspond aux critères imposés
     *
     * @param array $c Tableau contenant les données du commentaire que souhaite poster l'utilisateur
     * @return array Tableau qui retourne un message d'erreur s'il y a sinon null il sera, ainsi que le tableau du commentaire
     */
    public function checkComment(array $c): array {
        $error = null;
        $email_pseudo = $this->mdb->exec(
            "SELECT DISTINCT email, pseudo FROM comment WHERE email = :email OR pseudo = :pseudo",
            ['email' => $c['email'], 'pseudo' => $c['pseudo']]
        );

        if (empty($c['email'])) $error .= "<div>L'adresse email ne peut pas être vide !</div>";
        else if (!filter_var($c['email'], FILTER_VALIDATE_EMAIL))
            $error .= "<div>L'adresse email saisie n'est pas valide !</div>";

        if (empty($c['pseudo'])) {
            if (isset($error)) $error .= "<br>";
            $error .= "<div>Le pseudo ne peut pas être vide !</div>";
        }

        if (empty($c['commentaire'])) {
            if (isset($error)) $error .= "<br>";
            $error .= "<div>Le commentaire ne peut pas être vide !</div>";
        } else if (strlen(str_replace(' ', '', $c['commentaire'])) >= 150) {
            if (isset($error)) $error .= "<br>";
            $error .= "<div>Le commentaire ne doit pas dépasser 150 caractères (espaces non inclus) !</div>";
        }

        $i = 0;
        while ($i < count($email_pseudo)) {
            if (($email_pseudo[$i]['email'] == $c['email'] && $email_pseudo[$i]['pseudo'] != $c['pseudo']) ||
                ($email_pseudo[$i]['pseudo'] == $c['pseudo'] && $email_pseudo[$i]['email'] != $c['email'])) {
                if (isset($error)) $error .= "<br>";
                $error .= "<div>La paire e-mail/pseudo ne correspondent pas !</div>";
                $i = count($email_pseudo); // met fin à la boucle
            }
            $i++;
        }

        return array('error' => $error, 'comment' => $c);
    }

    /**
     * Ajoute le commentaire de l'utilisateur à la BDD et redirige sur la page du film pour actualiser
     *
     * @param array $comment Tableau des éléments du formulaire qu'à rempli l'utilisateur
     * @return void
     */
    public function addComment(array $comment): void {
        $this->mdb->addComment($comment);
        header("Location: " . __RPRJ__ . "pages/movie.php?id=" . $comment['movie_id'] . "#comments-container");
        exit();
    }

    /**
     * Génère le bouton qui affichera ou non le formulaire à remplir pour poster un commentaire sous un film et
     * génère le formulaire pour ajouter un commentaire sous un film
     *
     * @param array|null $c Tableau contenant les données du commentaire qu'à tenter de poster l'utilisateur sinon rien
     * @param string|null $message Message d'erreur si le commentaire n'est pas conforme sinon rien
     * @return void
     */
    public function getForm(array $c=null, string $message=null): void {?>
        <div id="add-comment">
            <button id="btn-comment" class="btn btn-outline-<?= (isset($message)) ? "danger" : "primary"?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chat-right-text me-3" viewBox="0 0 16 16">
                    <path d="M2 1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h9.586a2 2 0 0 1 1.414.586l2 2V2a1 1 0 0 0-1-1H2zm12-1a2 2 0 0 1 2 2v12.793a.5.5 0 0 1-.854.353l-2.853-2.853a1 1 0 0 0-.707-.293H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12z"/>
                    <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                </svg>
                <span id="btn-text" style="margin: 0"><?= (isset($message)) ? "Fermer" : "Ajouter votre avis"?></span>
            </button>
        </div>
        <hr>
        <form method="post" id="form-comment" name="addComment" class="<?php if (!isset($message)) echo "h-hide"?>">
            <div id="error" class="error"><?php if(isset($message)) echo $message ?></div>
            <div id="form-logs">
                <label for="commEmail" class="visually-hidden">Email</label>
                <input type="email" class="form-control" id="commEmail" name="commEmail" placeholder="Email"
                       value="<?php if (isset($c)) echo $c['email'] ?>">
                <label for="commPseudo" class="visually-hidden">Pseudo</label>
                <input type="text" class="form-control" id="commPseudo" name="commPseudo" placeholder="Pseudo"
                       value="<?php if (isset($c)) echo $c['pseudo'] ?>">
            </div>
            <div style="width: 100%">
                <label for="commText">Commentaire</label>
                <textarea class="form-control" id="commText" name="commText" rows="4"
                    <?php if (isset($message)) echo "autofocus"?>><?php if (isset($c)) echo $c['commentaire'] ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="margin: 20px 0 10px">Envoyer</button>
            <hr>
        </form>
        <?php
    }

    /**
     * Génère les éléments de la page du film correspondant
     *
     * @param array|null $resp Tableau avec les éléments nécessaires en cas d'erreur
     * @return void
     */
    public function generateMoviePage(array $resp=null): void {?>
        <div id="movie-container">
            <?php $this->movie[0]->getMovieHTML();?>
            <div id="comments-container">
                <?php
                if (isset($resp)) $this->getForm($resp['comment'], $resp['error']);
                else $this->getForm();
                foreach ($this->getComments() as $comment) $comment->getCommentHTML();?>
            </div>
        </div>
        <script src="<?= __RPRJ__ ?>js/movie.js"></script>
        <?php
    }
}