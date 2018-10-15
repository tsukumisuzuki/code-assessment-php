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
    public function getLocation(Request $request, Response $response) {
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

            if($result) {
                return json_encode($result);
            } else {
                return $response->withStatus(404)->write("id not found");
            }

        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }

    /**
     * @param Request $request
     * @return JSON
     */
    public function getLocationByName(Request $request, Response $response) {
        try {
            $name = $request->getAttribute('name');
            $dbh = Locations::getConnection();

            $sql = "SELECT *
                FROM locations
                WHERE location_name = :name";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam('name', $name);
            $stmt->execute();
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
    public function createLocation(Request $request) {
        try {
            $location = json_decode($request->getBody());
            $dbh = Locations::getConnection();

            $sql = "INSERT INTO locations
                (location_name, address, city, state, latitude, longitude, phone, country, postal_code)
                VALUES (:location_name, :address, :city, :state, :latitude, :longitude, :phone, :country, :postal_code)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam('location_name', $location->location_name);
            $stmt->bindParam('address', $location->address);
            $stmt->bindParam('city', $location->city);
            $stmt->bindParam('state', $location->state);
            $stmt->bindParam('latitude', $location->latitude);
            $stmt->bindParam('longitude', $location->longitude);
            $stmt->bindParam('phone', $location->phone);
            $stmt->bindParam('country', $location->country);
            $stmt->bindParam('postal_code', $location->postal_code);
            $stmt->execute();
            $location->id = $dbh->lastInsertId();


            return json_encode($location);
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }

    /**
     * @param Request $request
     * @return JSON
     */
    public function updateLocation(Request $request, Response $response) {
        try {
            $id = $request->getAttribute('id');
            $location = json_decode($request->getBody());
            $dbh = Locations::getConnection();

            $select_sql = "SELECT * FROM locations WHERE locations.id = :id";
            $stmt = $dbh->prepare($select_sql);
            $stmt->bindParam('id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if($result) {
                $update_sql = "UPDATE locations
                    SET
                    location_name=:location_name,
                    address=:address,
                    city=:city,
                    state=:state,
                    latitude=:latitude,
                    longitude=:longitude,
                    phone=:phone,
                    country=:country,
                    postal_code=:postal_code
                    WHERE id=:id";
                $stmt = $dbh->prepare($update_sql);
                $stmt->bindParam('location_name', $location->location_name);
                $stmt->bindParam('address', $location->address);
                $stmt->bindParam('city', $location->city);
                $stmt->bindParam('state', $location->state);
                $stmt->bindParam('latitude', $location->latitude);
                $stmt->bindParam('longitude', $location->longitude);
                $stmt->bindParam('phone', $location->phone);
                $stmt->bindParam('country', $location->country);
                $stmt->bindParam('postal_code', $location->postal_code);
                $stmt->bindParam('id', $id);
                $stmt->execute();

                return json_encode($location);
            } else {
                return $response->withStatus(404)->write("id not found");
            }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }

    /**
     * @param Request $request
     * @return JSON
     */
    public function deleteLocation(Request $request, Response $response) {
        try {
            $id = $request->getAttribute('id');
            $dbh = Locations::getConnection();

            $select_sql = "SELECT * FROM locations WHERE locations.id = :id";
            $stmt = $dbh->prepare($select_sql);
            $stmt->bindParam('id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if($result) {
                $delete_sql = "DELETE FROM locations WHERE locations.id = :id";
                $stmt = $dbh->prepare($delete_sql);
                $stmt->bindParam('id', $id);
                $stmt->execute();

                return '{"success":{"text": "success" }}';
            } else {
                return $response->withStatus(404)->write("id not found");
            }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }
}
