<?php

require_once '../database/DBConnection.php';
require_once '../database/DBConstants.php';
require_once 'IDAO.php';

abstract class GenericDAO implements IDAO
{

    private $db;
    private $reflection;
    public $dbh;

    function __construct()
    {
        $this->db = new DBConnection(DBConstants::DB_HOST,
            DBConstants::DB_PORT,
            DBConstants::DB_NAME,
            DBConstants::DB_USER,
            DBConstants::DB_PASS);
        $this->dbh = $this->db->getDbh();
        $this->reflection = new ReflectionClass($this->getEntityClass());
    }

    public function findAll(): array
    {
        try {
            $query = "SELECT * FROM " . strtolower($this->getEntityClass()) . ";";
            $stmt = $this->dbh->prepare($query);
            if ($stmt->execute()) {
                $result = [];
                while ($row = $stmt->fetch()) {
                    array_push($result, $this->mapToEntity($row));
                }
                return $result;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return [];
    }

    public function findById($id)
    {
        try {
            $query = "SELECT * FROM " . strtolower($this->getEntityClass()) . " WHERE id = :id;";
            $stmt = $this->dbh->prepare($query);
            $stmt->bindParam(':id', $id);
            if ($stmt->execute()) {
                while ($row = $stmt->fetch()) {
                    return $this->mapToEntity($row);
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return null;
    }

    public function insert($entity)
    {
        $properties = $this->reflection->getProperties(ReflectionProperty::IS_PRIVATE);
        try {
            $query = "INSERT INTO " . strtolower($this->getEntityClass()) . " (";
            foreach ($properties as $i => $prop) {
                if ($prop->name != $this->getIdField()) {
                    $query .= $prop->name;
                    if ($i < sizeof($properties) - 1) {
                        $query .= ", ";
                    }
                }
            }
            $query = $query . ") VALUES (";
            foreach ($properties as $i => $prop) {
                if ($prop->name != $this->getIdField()) {
                    $query .= "?";
                    if ($i < sizeof($properties) - 1) {
                        $query .= ", ";
                    }
                }
            }
            $query .= ");";
            $values = [];
            foreach ($properties as $prop) {
                $prop->setAccessible(true);
                if ($prop->name != $this->getIdField()) {
                    array_push($values, $prop->getValue($entity));
                }
                $prop->setAccessible(false);
            }
            print_r($values);
            print_r($query);
            $this->dbh->prepare($query)->execute($values);
            return $this->dbh->lastInsertId();
        } catch (Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function update($entity)
    {
        $properties = $this->reflection->getProperties(ReflectionProperty::IS_PRIVATE);
        try {
            $query = "UPDATE " . strtolower($this->getEntityClass()) . " SET ";
            foreach ($properties as $i => $prop) {
                if ($prop->name != $this->getIdField()) {
                    $query .= $prop->name . " = ?";
                    if ($i < sizeof($properties) - 1) {
                        $query .= ", ";
                    }
                }
            }
            $query = $query . " WHERE " . $this->getIdField() . " = ?" . ";";

            $values = [];
            foreach ($properties as $prop) {
                $prop->setAccessible(true);
                if ($prop->name != $this->getIdField()) {
                    array_push($values, $prop->getValue($entity));
                } else {
                    $id = $prop->getValue($entity);
                }
                $prop->setAccessible(false);
            }
            array_push($values, $id);
            $this->dbh->prepare($query)->execute($values);
        } catch (Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function delete($id)
    {
        try {
            $query = "DELETE FROM " . strtolower($this->getEntityClass()) . " WHERE id = ?;";
            $values = [$id];
            $this->dbh->prepare($query)->execute($values);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    abstract protected function getEntityClass();

    protected function mapToEntity($row)
    {
        $properties = $this->reflection->getProperties();
        $fields = [];
        foreach ($properties as $prop) {
            array_push($fields, $row[$prop->name]);
        }
        return $this->reflection->newInstanceArgs($fields);
    }

    protected function getIdField()
    {
        return "id";
    }
}
