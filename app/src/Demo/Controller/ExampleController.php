<?php

namespace Demo\Controller;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use PDO;

class ExampleController
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
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function getDefault(Request $request, Response $response, array $args = null) :Response
    {
        $response->getBody()->write("Get test successful!");
        return $response;
    }

    public function getEmployees($response) {
        try {
            $servername=$this->container['settings']['db']['host'] ;
            $username=$this->container['settings']['db']['user'] ;
            $password=$this->container['settings']['db']['pass'] ;
            $dbname=$this->container['settings']['db']['dbname'] ;
            $dbh = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM employees";
            $stmt = $dbh->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            return json_encode($result);
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }

    public function getEmployee(Request $request, Response $response) {
        try {
            $id = $request->getAttribute('id');
            $servername=$this->container['settings']['db']['host'] ;
            $username=$this->container['settings']['db']['user'] ;
            $password=$this->container['settings']['db']['pass'] ;
            $dbname=$this->container['settings']['db']['dbname'] ;
            $dbh = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM employees WHERE id = :id";
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
