<?php

require_once '../model/Answer.php';
require_once '../dao/AnswerDAO.php';

class AnswerController
{

    const ANSWER_ACTION_SUCCESS = 0;
    const ANSWER_ACTION_FAIL_DUE_MISSING_ENTRY = 1;

    private $dao;

    public function __construct()
    {
        $this->dao = new AnswerDAO();
    }

    function handleRequests($entryID)
    {
        if (isset($_POST['save']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($entryID !== null) {
                foreach (array_keys($_POST['answer']) as $index) {
                    $answer = new Answer(
                        -1,
                        $entryID,
                        $index,
                        $_POST['answer'][$index]
                    );
                    $this->dao->insert($answer);
                }
                return self::ANSWER_ACTION_SUCCESS;
            } else {
                return self::ANSWER_ACTION_FAIL_DUE_MISSING_ENTRY;
            }
        }
    }

}