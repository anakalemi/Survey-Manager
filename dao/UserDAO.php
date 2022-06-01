<?php

require_once 'GenericDAO.php';

class UserDAO extends GenericDAO
{

    protected function getEntityClass()
    {
        return 'User';
    }

    public function authenticateUser($username, $password)
    {
        try {
            $query = "SELECT * FROM " . strtolower($this->getEntityClass()) . " WHERE username= :username AND password= :password";
            $stmt = $this->dbh->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            if ($stmt->execute()) {
                while ($row = $stmt->fetch()) {
                    return $this->mapToEntity($row);
                }
            }
        } catch (Exception $e)
        {
            echo $e->getMessage();
        }
        return null;
    }

    public function getUserByUsername($username): array {
        $query = "SELECT * FROM user WHERE username = :username;";
        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam(':username', $username);
        if ($stmt->execute()) {
            $result = [];
            while ($row = $stmt->fetch()) {
                array_push($result, $this->mapToEntity($row));
            }
            return $result;
        }
        return [];
    }

}
