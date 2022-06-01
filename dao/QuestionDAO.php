<?php

require_once 'GenericDAO.php';

class QuestionDAO extends GenericDAO
{

    protected function getEntityClass()
    {
        return 'Question';
    }

    public function getQuestionsBySurveyID($survey_id): array {
        $query = "SELECT * FROM question WHERE survey_id = :id;";
        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam(':id', $survey_id);
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
