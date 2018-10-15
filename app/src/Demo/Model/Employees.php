<?php

namespace Demo\Model;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use PDO;

class Employees
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
    public function getEmployees() {
        try {
            $dbh = Employees::getConnection();

            $sql = "SELECT *
            FROM employees, job_titles
            WHERE employees.job_title_id = job_titles.id";
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
    public function getEmployee(Request $request, Response $response) {
        try {
            $id = $request->getAttribute('id');
            $dbh = Employees::getConnection();

            $sql = "SELECT *
                FROM employees, job_titles
                WHERE employees.job_title_id = job_titles.id
                  AND employees.id = :id";
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
    public function createEmployee(Request $request) {
        try {
            $employee = json_decode($request->getBody());
            $dbh = Employees::getConnection();

            $sql = "INSERT INTO employees
                (first_name, last_name, email, job_title_id)
                VALUES (:first_name, :last_name, :email, :job_title_id)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam('first_name', $employee->first_name);
            $stmt->bindParam('last_name', $employee->last_name);
            $stmt->bindParam('email', $employee->email);
            $stmt->bindParam('job_title_id', $employee->job_title_id);
            $stmt->execute();
            $employee->id = $dbh->lastInsertId();


            return json_encode($employee);
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }

    /**
     * @param Request $request
     * @return JSON
     */
    public function updateEmployee(Request $request, Response $response) {
        try {
            $id = $request->getAttribute('id');
            $employee = json_decode($request->getBody());
            $dbh = Employees::getConnection();

            $select_sql = "SELECT * FROM employees WHERE employees.id = :id";
            $stmt = $dbh->prepare($select_sql);
            $stmt->bindParam('id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if($result) {
                $sql = "UPDATE employees
                    SET
                    first_name=:first_name,
                    last_name=:last_name,
                    email=:email,
                    job_title_id=:job_title_id
                    WHERE id=:id";
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam('first_name', $employee->first_name);
                $stmt->bindParam('last_name', $employee->last_name);
                $stmt->bindParam('email', $employee->email);
                $stmt->bindParam('job_title_id', $employee->job_title_id);
                $stmt->bindParam('id', $id);
                $stmt->execute();

                return json_encode($employee);
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
    public function deleteEmployee(Request $request, Response $response) {
        try {
            $id = $request->getAttribute('id');
            $dbh = Employees::getConnection();

            $select_sql = "SELECT * FROM employees WHERE employees.id = :id";
            $stmt = $dbh->prepare($select_sql);
            $stmt->bindParam('id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if($result) {
                $delete_sql = "DELETE FROM employees WHERE employees.id = :id";
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
