<?php
require_once '../model/User.php';
session_start();
?>
<?php
$currentUser = $_SESSION['loggedUser'];
//Go back to Sign In page if no user is logged in
if (empty($currentUser) || $currentUser->getUserId() == null) {
    header("Location: SignIn.php");
    exit();
}
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Survey Manager</title>
    <link rel="stylesheet" type="text/css" href="../asset/css/home.css">
    <link rel="stylesheet" type="text/css" href="../asset/css/nav-bar.css">
</head>

<body>
<?php include 'reusable/NavBar.php' ?>
<div class="box-root flex-flex flex-direction--column" style="flex-grow: 1;">
    <div class="box-root padding-bottom--24 padding-top--24 flex-flex flex-direction--column"
         style="flex-grow: 1; z-index: 9;">
        <div class="box-root padding-top--24 padding-bottom--24 flex-flex flex-justifyContent--center">
            <h1>Statistics</h1>
        </div>
        <div class="formbg">
            <div class="padding-horizontal--48">
                <?php
                require_once '../dao/SurveyDAO.php';
                require_once '../dao/EntryDAO.php';
                require_once '../dao/QuestionDAO.php';
                require_once '../model/Survey.php';
                require_once '../model/Entry.php';
                require_once '../model/Answer.php';
                require_once '../model/Question.php';

                $surveyDAO = new SurveyDAO();
                $entryDAO = new EntryDAO();
                $questionDAO = new QuestionDAO();
                $answerDAO = new AnswerDAO();

                $currentSurveyID = $_GET['surveyID'];
                $currentSurvey = $surveyDAO->findById($currentSurveyID);

                if ((empty($currentSurvey) || $currentSurvey->isPublished() == Survey::NOT_PUBLISHED)) {
                    header("Location: Home.php");
                    exit();
                }

                $questionsOfCurrentSurvey = $questionDAO->getQuestionsBySurveyID($currentSurveyID);
                $entriesOfCurrentSurvey = $entryDAO->getEntriesBySurveyId($currentSurveyID);

                $answersOfEntries = [];
                foreach ($entriesOfCurrentSurvey as $entry) {
                    array_push($answersOfEntries, $answerDAO->getAnswersByEntryID($entry->getId()));
                }

                $numberOfEntries = sizeof($entriesOfCurrentSurvey);
                ?>
                <span class="padding-bottom--24 flex-flex flex-justifyContent--center">
                    <a href="<?php echo 'Entries.php?surveyID='.$currentSurveyID?>">Entries - <?php echo $numberOfEntries ?> </a></span>
                <?php
                $datesArray = [];
                $totalN = null;
                if ($numberOfEntries != 0) {
                foreach ($questionsOfCurrentSurvey as $question) {
                foreach ($answersOfEntries as $answerArray) {
                    foreach ($answerArray as $answer) {
                        if ($answer->getQuestionId() == $question->getId()) {
                            switch ($question->getType()) {
                                case Question::TYPE_NUMBER:
                                    $totalN += $answer->getContent();
                                    break;
                                case Question::TYPE_DATE:
                                    array_push($datesArray, $answer->getContent());
                                    break;
                                default:
                                    $totalN = 0;
                                    break;
                            }
                        }
                    }
                }
                ?>
                <div class="field padding-bottom--24 padding-top--15">
                    <span><?php echo $question->getDescription() ?></span>
                </div>
                <div class="field">
                    <label>Average Answer</label>
                    <?php
                    switch ($question->getType()) {
                        case Question::TYPE_TEXT:
                            ?>
                            <input style="width: 100%" type="text" disabled
                                   value="No data can be generated for text type questions">
                            <?php
                            break;
                        case Question::TYPE_NUMBER:
                            ?>
                            <input style="width: 100%" type="text" disabled
                                   value="<?php echo round($totalN / $numberOfEntries); ?>">
                            <?php
                            break;
                        case Question::TYPE_DATE:
                            ?>
                            <input style="width: 100%" type="text" disabled
                                   value="<?php echo computeDatesAverage($datesArray, $numberOfEntries); ?>">
                            <?php
                            break;
                        }
                    }
                }
                function computeDatesAverage($datesArray, $n): string
                {
                    $y = null;
                    $m = null;
                    $d = null;
                    foreach ($datesArray as $date) {
                        $y += date('Y', strtotime($date));
                        $m += date('m', strtotime($date));
                        $d += date('d', strtotime($date));
                    }
                        return round($y / $n) . '-' . round($m / $n) . '-' . round($d / $n);
                }
                ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>