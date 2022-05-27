<?php
namespace mdb;

class Diapo
{
    private array $movies; // Tableau de tous les films de la BDD

    public function __construct() {
        $mdb = new MoviesDB();
        $this->movies = $mdb->exec(
            "SELECT titre, date_sortie, img, synopsis FROM film",
            null,
            "\mdb\MovieRenderer"
        );
    }

    /**
     * Affiche un diaporama avec des films choisis au hasard
     *
     * @return void
     */
    public function getDiapo(): void {
        $rand1 = rand(0, count($this->movies) - 1);
        do {
            $rand2 = rand(0, count($this->movies) - 1);
        } while ($rand2 == $rand1);?>
        <div id="slider" class="carousel carousel-dark slide h-md-80">
            <div id="carousel-inner" class="carousel-inner">
                <?php
                $this->movies[$rand1]->getDiapoHTML(true);
                $this->movies[$rand2]->getDiapoHTML();?>
            </div>
            <div id="btn-random-container">
                <button id="btn-random" type="button" data-bs-target="#slider" data-bs-slide="next">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-dice-3-fill" viewBox="0 0 16 16">
                        <path d="M3 0a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V3a3 3 0 0 0-3-3H3zm2.5 4a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm8 8a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zM8 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                    </svg>
                </button>
            </div>
            <input type="hidden" id="moviesList" value="<?= htmlentities(json_encode($this->movies)); ?>">
        </div>
        <script src="<?= __RPRJ__ ?>js/carousel.js"></script>
        <?php
    }

}