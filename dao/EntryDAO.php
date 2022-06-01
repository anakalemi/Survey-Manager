<?php

require_once 'GenericDAO.php';
require_once 'AnswerDAO.php';

class EntryDAO extends GenericDAO
{

    protected function getEntityClass(): string
    {
        return 'Entry';
    }

    public function getEntryAnswers($id): array
    {
        $answerDAO = new AnswerDAO();
        return $answerDAO->getAnswersByEntryID($id);
    }

    public function getEntriesByUserId($userId): array
    {
        $query = "SELECT * FROM entry WHERE user_id = :id;";
        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam(':id', $userId);
        if ($stmt->execute()) {
            $result = [];
            while ($row = $stmt->fetch()) {
                array_push($result, $this->mapToEntity($row));
            }
            return $result;
        }
        return [];
    }

    public function getEntriesBySurveyId($surveyId): array
    {
        $query = "SELECT * FROM entry WHERE survey_id = :id;";
        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam(':id', $surveyId);
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
