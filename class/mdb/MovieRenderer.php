<?php

namespace mdb;

class MovieRenderer
{
    public function getDiapoHTML(bool $isFirst=false) {?>
        <div class="carousel-item<?php if ($isFirst) echo " active";?>">
            <div class="carousel-container">
                <img src="<?= __RIMG__ . $this->img ?>" class="d-block h-100">
                <div class="carousel-infos-container">
                    <div class="carousel-infos">
                        <h4><?= $this->titre ?></h4>
                        <span><?= $this->date_sortie ?></span>
                        <span class="carousel-synopsis"><?= $this->synopsis ?></span>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    public function getCardsHTML(bool $logged) {?>
        <div class="card card-browse m-2">
            <?php if ($logged): ?>
                <a href="<?= __RPRJ__ ?>pages/create.php?id=<?= $this->id ?>" id="btn-edit" class="btn btn-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                      <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                      <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                    </svg>
                </a>
            <?php endif; ?>
            <a href="<?= __RPRJ__ ?>pages/movie.php?id=<?= $this->id ?>" id="card-overlay" class="card-img-overlay text-white">
                <div>
                    <b><?= $this->titre ?></b>
                    <div><?= $this->date_sortie ?></div>
                </div>
            </a>
            <img src="<?= __RIMG__ . $this->img ?>">
        </div>
    <?php
    }

    public function getMovieHTML() {?>
        <div id="fiche-container">
            <h1><?= $this->titre ?></h1>
            <hr>
            <div id="fiche-content">
                <img src="<?= __RIMG__ . $this->img ?>">
                <div id="fiche-description">
                    <div>
                        <h5>Année de sortie :</h5>
                        <p><?= $this->date_sortie ?></p>
                    </div>
                    <div>
                        <h5>Synopsis :</h5>
                        <p><?= $this->synopsis ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
}