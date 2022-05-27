<?php
namespace pdo_wrapper;
use \PDO;

class PdoWrapper
{
    private string $db_name, $db_user, $db_pwd, $db_host, $db_port;
    private PDO $pdo;

    public function __construct($db_name, $db_host='127.0.0.1', $db_port='3306', $db_user = 'root', $db_pwd='root') {
        $this->db_name = $db_name; $this->db_host = $db_host; $this->db_port = $db_port;
        $this->db_user = $db_user; $this->db_pwd = $db_pwd;

        $dsn = 'mysql:dbname=' . $this->db_name . ';host='. $this->db_host. ';port=' . $this->db_port;
        try {
            $this->pdo = new PDO($dsn, $this->db_user, $this->db_pwd);
        } catch (\Exception $ex) {
            die('Error : ' . $ex->getMessage());
        }
    }

    /**
     * Exécute une requête passée en entrée
     *
     * @param string $query La requête à faire
     * @param array|null $params Tableau de paramètres si nécessaire contenant des valeurs (ou paramètres) correspondants à la requête $query
     * @param string|null $args L'argument du fetchAll, correspondant à l'adresse de la classe, sinon aucun argument donné au fetchAll
     * @return array|object Tableau ou Objet contenant le(s) résultat(s) de la requête passée en paramètre de la fonction
     */
    public function exec(string $query, array $params=null, string $args=null): array|object {
        $statement = $this->pdo->prepare($query);
        $statement->execute($params) or die(var_dump($statement->errorInfo()));
        if (isset($args)) return $statement->fetchAll(PDO::FETCH_CLASS, $args);
        return $statement->fetchAll();
    }
}