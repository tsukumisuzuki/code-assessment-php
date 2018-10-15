<?php

namespace Demo\Model;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use PDO;

class Locations
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return PDO
     */
    public function getConnection() {
        $servername=$this->container['settings']['db']['host'] ;
        $username=$this->container['settings']['db']['user'] ;
        $password=$this->container['settings']['db']['pass'] ;
        $dbname=$this->container['settings']['db']['dbname'] ;
        $dbh = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $dbh;
    }

    /**
     * @return JSON
     */
    public function getLocations() {
        try {
            $dbh = Locations::getConnection();

            $sql = "SELECT *
            FROM locations";
            $stmt = $dbh->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            return json_encode($result);
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }

    /**
     * @param Request $request
     * @return JSON
     */
    public function getLocation(Request $request) {
        try {
            $id = $request->getAttribute('id');
            $dbh = Locations::getConnection();

            $sql = "SELECT *
                FROM locations
                WHERE id = :id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam('id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            return json_encode($result);
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }
}
