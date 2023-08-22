<?php

namespace Otto;

use PDO;
use PDOException;

class Challenge
{
    protected $pdoBuilder;

    public function __construct()
    {
        $config = require __DIR__ . '/../config/database.config.php';
        $this->setPdoBuilder(new PdoBuilder($config));
    }

    /**
     * Use the PDOBuilder to retrieve all the records
     *
     * @return array
     */
    public function getRecords() 
    {
        // TODO

        $pdo = $this->pdoBuilder->getPdo();

        $query = "
        SELECT d.id, d.first_name, d.last_name, b.name, b.registered_address, b.registration_number
        FROM directors d
        JOIN director_businesses db ON d.id = db.director_id
        JOIN businesses b ON db.business_id = b.id
        ";

        try {
            $statement = $pdo->prepare($query);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the exception, e.g., log the error or show a user-friendly message
            return []; // Return an empty array in case of an error
        }
    }

    /**
     * Use the PDOBuilder to retrieve all the director records
     *
     * @return array
     */
    public function getDirectorRecords() 
    {

        $pdo = $this->pdoBuilder->getPdo();

        $query = "
            SELECT d.id, d.first_name, d.last_name, d.occupation, d.date_of_birth
            FROM directors d
        ";

        try {
            $statement = $pdo->prepare($query);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the exception, e.g., log the error or show a user-friendly message
            return []; // Return an empty array in case of an error
        }
    }

    /**
     * Use the PDOBuilder to retrieve a single director record with a given id
     *
     * @param int $id
     * @return array
     */
    public function getSingleDirectorRecord($id)
    {
        $pdo = $this->pdoBuilder->getPdo();

        $query = "
            SELECT d.id, d.first_name, d.last_name, d.occupation, d.date_of_birth
            FROM directors d
            WHERE d.id = :id
        ";

        try {
            $statement = $pdo->prepare($query);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the exception, e.g., log the error or show a user-friendly message
            return null; // Return null in case of an error
        }
    }

    /**
     * Use the PDOBuilder to retrieve all the business records
     *
     * @return array
     */
    public function getBusinessRecords() 
    {
        $pdo = $this->pdoBuilder->getPdo();

        $query = "
            SELECT b.id, b.name, b.registered_address, b.registration_date, b.registration_number
            FROM businesses b
        ";

        try {
            $statement = $pdo->prepare($query);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the exception, e.g., log the error or show a user-friendly message
            return []; // Return an empty array in case of an error
        }
    }

    /**
     * Use the PDOBuilder to retrieve a single business record with a given id
     *
     * @param int $id
     * @return array
     */
    public function getSingleBusinessRecord($id) 
    {
        $pdo = $this->pdoBuilder->getPdo();

        $query = "
            SELECT b.id, b.name, b.registered_address, b.registration_date, b.registration_number
            FROM businesses b
            WHERE b.id = :id
        ";

        try {
            $statement = $pdo->prepare($query);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the exception, e.g., log the error or show a user-friendly message
            return null; // Return null in case of an error
        }
    }

    /**
     * Use the PDOBuilder to retrieve a list of all businesses registered on a particular year
     *
     * @param int $year
     * @return array
     */
    public function getBusinessesRegisteredInYear($year)
    {
        $pdo = $this->pdoBuilder->getPdo();

        $query = "
            SELECT b.id, b.name, b.registered_address, b.registration_date, b.registration_number
            FROM businesses b
            WHERE YEAR(b.registration_date) = :year
        ";
    
        try {
            $statement = $pdo->prepare($query);
            $statement->bindParam(':year', $year, PDO::PARAM_INT);
            $statement->execute();
    
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the exception, e.g., log the error or show a user-friendly message
            return []; // Return an empty array in case of an error
        }
    }

    /**
     * Use the PDOBuilder to retrieve the last 100 records in the directors table
     *
     * @return array
     */
    public function getLast100Records()
    {
        $pdo = $this->pdoBuilder->getPdo();

        $query = "
            SELECT d.id, d.first_name, d.last_name, d.occupation, d.date_of_birth
            FROM directors d
            ORDER BY d.id DESC
            LIMIT 100
        ";

        try {
            $statement = $pdo->prepare($query);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the exception, e.g., log the error or show a user-friendly message
            return []; // Return an empty array in case of an error
        }
    }

    /**
     * Use the PDOBuilder to retrieve a list of all business names with the director's name in a separate column.
     * The links between directors and businesses are located inside the director_businesses table.
     *
     * Your result schema should look like this;
     *
     * | business_name | director_name |
     * ---------------------------------
     * | some_company  | some_director |
     *
     * @return array
     */
    public function getBusinessNameWithDirectorFullName()
    {
        $pdo = $this->pdoBuilder->getPdo();

        $query = "
            SELECT b.name AS business_name, CONCAT(d.first_name, ' ', d.last_name) AS director_name
            FROM businesses b
            JOIN director_businesses db ON b.id = db.business_id
            JOIN directors d ON db.director_id = d.id
        ";

        try {
            $statement = $pdo->prepare($query);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the exception, e.g., log the error or show a user-friendly message
            return []; // Return an empty array in case of an error
        }
    }

    /**
     * @param PdoBuilder $pdoBuilder
     * @return $this
     */
    public function setPdoBuilder(PdoBuilder $pdoBuilder)
    {
        $this->pdoBuilder = $pdoBuilder;
        return $this;
    }

    /**
     * @return PdoBuilder
     */
    public function getPdoBuilder()
    {
        return $this->pdoBuilder;
    }
}
