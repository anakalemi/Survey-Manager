<?php

require_once '../model/Question.php';
require_once '../model/Survey.php';
require_once '../dao/QuestionDAO.php';
require_once '../controller/SurveyController.php';

class QuestionController
{
    const QUESTION_ACTION_SUCCESS = 0;
    const QUESTION_ACTION_FAIL_DUE_MISSING_SURVEY = 1;

    private $dao;

    public function __construct()
    {
        $this->dao = new QuestionDAO();
    }

    function handleRequests()
    {
        if (isset($_POST['save']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

            $surveyController = new SurveyController();
            $survey = $surveyController->dao->getByTitle($_POST['title']);

            if (!empty($survey)) {
                foreach (array_keys($_POST['question']) as $index) {

                    $question = new Question(
                        -1,
                        $_POST['question'][$index],
                        $_POST['aType'][$index],
                        $survey->getId() ?? -1);
                    $this->dao->insert($question);
                }
                return self::QUESTION_ACTION_SUCCESS;
            } else {
                return self::QUESTION_ACTION_FAIL_DUE_MISSING_SURVEY;
            }
        }

        if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentSurveyID = $_GET['surveyID'];

            $previousQuestions = $this->dao->getQuestionsBySurveyID($currentSurveyID);
            $currentQIDArray = array_keys($_POST['question']);

            $previousQIDArray = [];
            foreach ($previousQuestions as $question) {
                array_push($previousQIDArray, $question->getId());
            }

//            Delete the questions that were removed from the survey
            foreach ($previousQuestions as $prev) {
                if (in_array($prev->getId(), $currentQIDArray) == false) {
                    $this->dao->delete($prev->getId());
                }
            }

            if ($currentSurveyID) {
                foreach ($currentQIDArray as $index) {
                    $question = new Question(
                        $index,
                        $_POST['question'][$index],
                        $_POST['aType'][$index],
                        $currentSurveyID ?? -1);
                    $this->dao->update($question);
                    if (in_array($index, $previousQIDArray) == false) {
                        $this->dao->insert($question);
                    }
                }
                return self::QUESTION_ACTION_SUCCESS;
            } else {
                return self::QUESTION_ACTION_FAIL_DUE_MISSING_SURVEY;
            }
        }
    }

}