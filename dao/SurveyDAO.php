<?php

require_once 'GenericDAO.php';
require_once 'QuestionDAO.php';

class SurveyDAO extends GenericDAO
{

    protected function getEntityClass(): string
    {
        return 'Survey';
    }

    public function getSurveysQuestions($id): array
    {
        $questionDAO = new QuestionDAO();
        return $questionDAO->getQuestionsBySurveyID($id);
    }

    public function getByTitle($title)
    {
        $query = "SELECT * FROM survey WHERE title= :title;";
        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam(':title', $title);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                return $this->mapToEntity($row);
            }
        }
        return null;
    }

    public function getSurveysByUserId($userId): array
    {
        $query = "SELECT * FROM survey WHERE user_id = :id;";
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

}
