<?php

require_once '../model/Entry.php';
require_once '../dao/EntryDAO.php';

class EntryController
{
    const ENTRY_ACTION_SUCCESS = 0;
    const ENTRY_ACTION_FAIL = -1;

    public $dao;

    public function __construct()
    {
        $this->dao = new EntryDAO();
    }

    function handleRequests()
    {
        if (isset($_POST['save']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentUser = $_SESSION['loggedUser'] ?? null;
            $currentUserID = null;
            if($currentUser != null) {
                $currentUserID = $currentUser->getUserId();
            }

            $currentSurveyID = $_GET['surveyID'];

            $allEntries = $this->dao->findAll();
            foreach ($allEntries as $e) {
                if($e->getSurveyId() == $currentSurveyID
                    && $currentUserID !== null
                    && $e->getUserId() == $currentUserID) {
                    return self::ENTRY_ACTION_FAIL;
                }
            }
            $entry = new Entry(-1, $currentUserID, $currentSurveyID);
            return $this->dao->insert($entry);
        }
    }

}