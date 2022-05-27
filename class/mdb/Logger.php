<?php

namespace mdb;

class Logger
{
    /**
     * Génère le formulaire de connexion
     *
     * @param string|null $username Nom d'utilisateur fourni par l'utilisateur
     * @param string|null $message Message d'erreur s'il y a
     * @return void
     */
    public function generateLoginForm(string $username=null, string $message=null): void{?>
        <form method="post" id="login-form">
            <legend>Connexion</legend>
            <div id="error" class="error">
            <?php if ($message != null): ?>
                <div><?php echo $message ?></div>
            <?php endif; ?>
            </div>
            <div class="input-group">
                <span class="input-group-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                      <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                    </svg>
                </span>
                <input type="text" class="form-control" name="username" placeholder="Nom d'utilisateur" value="<?php echo $username ?>" autofocus>
            </div>
            <div class="input-group">
                <span class="input-group-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                      <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                    </svg>
                </span>
                <input type="password" class="form-control" name="password" placeholder="Mot de passe">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <script src="<?= __RPRJ__ ?>js/login.js"></script>
        <?php
    }

    /**
     * Vérifie si les informations de connexion transmises correspondent aux informations attendues
     *
     * @param string $username Nom d'utilisateur fourni par l'utilisateur
     * @param string $password Mot de passe fourni par l'utilisateur
     * @return array Tableau qui retourne un booléen si la connexion est autorisée, ainsi qu'un message d'erreur s'il y a sinon null
     */
    public function checkLogs(string $username, string $password) : array {
        // user = nanard ; pwd = ihatenanar
        $error = null;
        $granted = false;

        if (empty($username)) $error = "Le nom d'utilisateur ne peut pas être vide !";
        else {
            $mdb = new MoviesDB();
            $user = $mdb->exec(
                "SELECT * FROM user WHERE username = :username",
                ['username' => $username]
            )[0];
            if (isset($user) and password_verify($password, $user['password'])) {
                $granted = true;

                // Vérifie si le password a besoin d'être rehash afin d'éviter les failles de sécurités
                if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                    $mdb->exec(
                        "UPDATE user SET password = :password WHERE id = :id",
                        ['password' => password_hash($password, PASSWORD_DEFAULT)]
                    );
                }
            }
            else $error = "Le nom d'utilisateur ou le mot de passe est invalide !";
        }
        return array('granted' => $granted, 'error' => $error, 'username' => $username);
    }
}