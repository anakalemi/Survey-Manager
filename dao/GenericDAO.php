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
            return [];
        } catch (Exception $e) {
            echo $e->getMessage();
            return [];
        }
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
            return null;
        } catch (Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function insert($entity)
    {
        try {
            $query = "INSERT INTO " . strtolower($this->getEntityClass()) . " (";
            $fields = $this->getFields();
            for ($i = 0; $i < sizeof($fields); $i++) {
                if ($fields[$i] != $this->getIdField()) {
                    $query = $query . $fields[$i];
                    if ($i < sizeof($fields) - 1) {
                        $query = $query . ", ";
                    }
                }
            }

            $query = $query . ") VALUES (";
            for ($i = 0; $i < sizeof($fields); $i++) {
                if ($fields[$i] != $this->getIdField()) {
                    $query = $query . "?";
                    if ($i < sizeof($fields) - 1) {
                        $query = $query . ", ";
                    }
                }
            }
            $query = $query . ");";
            $values = [];
            for ($i = 0; $i < sizeof($fields); $i++) {
                if ($fields[$i] != $this->getIdField()) {
                    array_push($values, $entity->{$fields[$i]});
                }
            }
            $this->dbh->prepare($query)->execute($values);
            return $this->dbh->lastInsertId();
        } catch (Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function update($entity)
    {
        try {
            $query = "UPDATE " . strtolower($this->getEntityClass()) . " SET ";
            $fields = $this->getFields();
            for ($i = 0; $i < sizeof($fields); $i++) {
                if ($fields[$i] != $this->getIdField()) {
                    $query = $query . $fields[$i] . " = ?";
                    if ($i < sizeof($fields) - 1) {
                        $query = $query . ", ";
                    }
                }
            }
            $query = $query . " WHERE " . $this->getIdField() . " = ?" . ";";

            $values = [];
            for ($i = 0; $i < sizeof($fields); $i++) {
                if ($fields[$i] != $this->getIdField()) {
                    array_push($values, $entity->{$fields[$i]});
                }
            }
            array_push($values, $entity->{$this->getIdField()});

            $this->dbh->prepare($query)->execute($values);
        } catch (Exception $e) {
            echo $e->getMessage();
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

    protected function getFields(): array
    {
        $properties = $this->reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $fields = [];
        foreach ($properties as $prop) {
            array_push($fields, $prop->getName());
        }
        return $fields;
    }

    protected function mapToEntity($row)
    {
        $fields = $this->getFields();
        $properties = [];
        foreach ($fields as $field) {
            array_push($properties, $row[$field]);
        }
        return $this->reflection->newInstanceArgs($properties);
    }

    protected function getIdField()
    {
        return "id";
    }
}
