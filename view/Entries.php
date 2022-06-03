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
            <h1>Entries</h1>
        </div>
        <div class="formbg">
            <div class="padding-horizontal--48">
                <?php
                require_once '../dao/SurveyDAO.php';
                require_once '../dao/EntryDAO.php';
                require_once '../dao/QuestionDAO.php';
                require_once '../dao/UserDAO.php';
                require_once '../model/Survey.php';
                require_once '../model/Entry.php';
                require_once '../model/Answer.php';
                require_once '../model/Question.php';
                require_once '../model/User.php';

                $surveyDAO = new SurveyDAO();
                $entryDAO = new EntryDAO();
                $questionDAO = new QuestionDAO();
                $answerDAO = new AnswerDAO();
                $userDAO = new UserDAO();

                $currentSurveyID = $_GET['surveyID'];
                $currentSurvey = $surveyDAO->findById($currentSurveyID);

                $questionsOfCurrentSurvey = $questionDAO->getQuestionsBySurveyID($currentSurveyID);
                $entriesOfCurrentSurvey = $entryDAO->getEntriesBySurveyId($currentSurveyID);

                $answersOfEntries = [];
                foreach ($entriesOfCurrentSurvey as $entry) {
                    array_push($answersOfEntries, $answerDAO->getAnswersByEntryID($entry->getId()));
                }

                $numberOfEntries = sizeof($entriesOfCurrentSurvey);

                if ((empty($currentSurvey)
                        || $currentSurvey->isPublished() == Survey::NOT_PUBLISHED)
                    || $numberOfEntries == 0) {
                    header("Location: Home.php");
                    exit();
                }
                ?>
                <span class="padding-bottom--24 flex-flex flex-justifyContent--center">
                    <a href="Entries.php">Entries - <?php echo $numberOfEntries ?> </a></span>
                <?php
                if ($numberOfEntries !== 0) {
                foreach ($questionsOfCurrentSurvey as $question) {
                ?>
                <div class="field padding-bottom--24 padding-top--15">
                    <span><?php echo $question->getDescription() ?></span>
                </div>
                <div class="field">

                    <?php
                    foreach ($answersOfEntries as $answerArray) {
                        foreach ($answerArray as $answer) {
                            if ($answer->getQuestionId() == $question->getId()) {
                                $currentEntry = $entryDAO->findById($answer->getEntryId());
                                $entryUser = $userDAO->findById($currentEntry->getUserId() ?? -1);
                                if (!is_null($entryUser)) {
                                    $entryUserName = $entryUser->getName();
                                } else {
                                    $entryUserName = 'anon.';
                                }
                                ?>
                                <label><?php echo $entryUserName; ?></label>
                                <input style="width: 100%" type="text" disabled
                                       value="<?php echo $answer->getContent(); ?>">

                                <?php
                            }
                        }
                    }
                    }
                    }
                    ?>
                    <div class="field padding-top--64">
                        <input type="button" name="cancel" id="cancel" style="margin-left: 10px;" value="Back">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("cancel").onclick = function () {
            window.location.href = 'Statistics.php?surveyID=' + <?php echo $currentSurveyID?>;
        };
    </script>
</body>
</html>