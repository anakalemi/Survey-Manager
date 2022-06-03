<?php

require_once '../model/Survey.php';
require_once '../dao/SurveyDAO.php';

class SurveyController
{

    const SURVEY_ACTION_SUCCESS = 0;
    const SURVEY_ACTION_FAIL_DUE_TITLE_DUB = 1;
    const SURVEY_ACTION_PUBLISHED = 2;

    public $dao;

    public function __construct()
    {
        $this->dao = new SurveyDAO();
    }

    function handleRequests()
    {
        if (isset($_POST['save']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentUser = $_SESSION['loggedUser'];
            $user_id = $currentUser->getUserId();

            $title = $_POST['title'];
            $survey = new Survey(-1, $title, $user_id, Survey::NOT_PUBLISHED);
            $result = $this->dao->getByTitle($title);

            if ($result == null) {
                $this->dao->insert($survey);
                return self::SURVEY_ACTION_SUCCESS;
            } else {
                return self::SURVEY_ACTION_FAIL_DUE_TITLE_DUB;
            }
        }

        if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentSurveyID = $_GET['surveyID'];
            $currentSurvey = $this->dao->findById($currentSurveyID);

            $isUnique = true;
            $allSurveys = $this->dao->findAll();

            if (!empty($allSurveys)) {
                foreach ($allSurveys as $survey) {
                    if ($_POST['title'] === $survey->getTitle()
                        && $currentSurvey->getId() !== $survey->getId())
                        $isUnique = false;
                }
            }

            if ($isUnique && ($_POST['title'] !== null)) {
                $updatedSurvey = new Survey($currentSurvey->getId(),
                    $_POST['title'],
                    $_SESSION['loggedUser']->getUserId(),
                    Survey::NOT_PUBLISHED);
                $this->dao->update($updatedSurvey);
                return self::SURVEY_ACTION_SUCCESS;
            } else {
                return self::SURVEY_ACTION_FAIL_DUE_TITLE_DUB;
            }
        }

        if ((isset($_POST['publish'])) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentSurveyID = $_GET['surveyID'];
            $currentSurvey = $this->dao->findById($currentSurveyID);
            $updatedSurvey = new Survey($currentSurvey->getId(),
                $currentSurvey->getTitle(),
                $currentSurvey->getUserId(),
                Survey::PUBLISHED);

            $this->dao->update($updatedSurvey);
            return self::SURVEY_ACTION_PUBLISHED;
        }

    }

}