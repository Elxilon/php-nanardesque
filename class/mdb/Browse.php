<?php

namespace mdb;

class Browse
{
    private static int $LIMIT = 12; // Nb de films affichés par page

    private int $curr_page, $total_pages;
    private string $str, $sort, $order;
    private array $movies; // Tableau de tous les films qui correspondent à la recherche

    public function __construct(int $curr_page, array $search) {
        $this->curr_page = $curr_page;
        $this->str = $search['str']; $this->sort = $search['sort']; $this->order = $search['order'];

        $mdb = new MoviesDB();
        $this->movies = $mdb->exec(
            "SELECT * FROM film WHERE titre LIKE '%" . $this->str . "%'ORDER BY " . $this->sort . " " . $this->order,
            null,
            "\mdb\MovieRenderer"
        );

        $this->total_pages = ceil(count($this->movies) / $this::$LIMIT);
    }

    /**
     * Affiche la barre pour trier la liste des films et la barre est actualisée aux paramètres précédemment recherchés
     * @return void
     */
    public function getSearch(): void {?>
        <div id="browse-search" class="p-regress">
            <button type="submit" class="btn btn-outline-dark">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg>
            </button>
            <div id="search-text">
                <label class="visually-hidden" for="searchString">Recherche</label>
                <input type="text" name="searchString" id="searchString" class="form-control" placeholder="Rechercher..." value="<?= $this->str ?>">
            </div>
            <div>
                <label class="visually-hidden" for="searchSort">Tri</label>
                <div class="input-group">
                    <div class="input-group-text d-none d-md-block">Tri</div>
                    <select class="form-select br-md-25" name="searchSort" id="searchSort">
                        <option <?php if ($this->sort == 'id') echo "selected";?> disabled value="id">Choisissez...</option>
                        <option <?php if ($this->sort == 'titre') echo "selected";?> value="titre">Alphabétique</option>
                        <option <?php if ($this->sort == 'date_sortie') echo "selected";?> value="date_sortie">Date de sortie</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="visually-hidden" for="searchOrder">Ordre</label>
                <select class="form-select" name="searchOrder" id="searchOrder">
                    <option <?php if ($this->order != 'desc') echo "selected";?> value="asc">Croissant</option>
                    <option <?php if ($this->order == 'desc') echo "selected";?> value="desc">Décroissant</option>
                </select>
            </div>
        </div>
        <?php
    }

    /**
     * Affiche le numéro de la page courante et le nombre de pages totales
     * et permet une navigation (avec deux boutons) entre les pages
     * @return void
     */
    public function getPages(): void {?>
        <div id="pages">
            <button class="btn btn-outline-dark" <?php if ($this->curr_page <= 1) echo "disabled";?> name="page" value="<?= $this->curr_page - 1 ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
            </button>
            <span id="current-page"><?= $this->curr_page ?></span>
            <span>/</span>
            <span id="total-page"><?= $this->total_pages ?></span>
            <button class="btn btn-outline-dark" <?php if ($this->curr_page >= $this->total_pages) echo "disabled";?> name="page" value="<?= $this->curr_page + 1 ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                </svg>
            </button>
        </div>
        <?php
    }

    /**
     * Affiche la liste des films correspondants à la recherche sous forme d'affiches,
     * chaque film est cliquable, et quand l'admin est log chaque film possède un bouton pour l'éditer
     *
     * @param bool $logged Booléen qui permet de savoir si l'utilisateur est log ou non
     */
    public function getBrowse(bool $logged): void {?>
        <form id="browser-container" name="browseSearch">
            <?php $this->getSearch(); ?>
            <div id="browser">
                <div id="browser-content" class="row g-4 w-regress">
                    <?php
                    $i = ($this->curr_page - 1) * $this::$LIMIT;
                    while ($i < count($this->movies) && $i < ($this->curr_page - 1) * $this::$LIMIT + $this::$LIMIT) {
                        $this->movies[$i]->getCardsHTML($logged);
                        $i++;
                    }?>
                </div>
            </div>
            <?php if ($this->total_pages > 1) $this->getPages();?>
        </form>
        <?php
    }

}