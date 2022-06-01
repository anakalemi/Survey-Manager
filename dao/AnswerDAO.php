<?php

require_once 'GenericDAO.php';

class AnswerDAO extends GenericDAO
{

    protected function getEntityClass(): string
    {
        return 'Answer';
    }

    public function getAnswersByEntryID($entry_id): array {
        $query = "SELECT * FROM answer WHERE entry_id = :id;";
        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam(':id', $entry_id);
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
