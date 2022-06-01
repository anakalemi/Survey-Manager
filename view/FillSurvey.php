<?php
require_once '../model/User.php';
session_start();
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Survey Manager</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../asset/css/home.css">
    <link rel="stylesheet" type="text/css" href="../asset/css/nav-bar.css">
    <link rel="stylesheet" type="text/css" href="../asset/css/createSurvey.css">
</head>
<body>
<?php
$currentUser = $_SESSION['loggedUser'] ?? null;
if (empty($currentUser) || $currentUser->getUserId() == null) {
    include 'reusable/UnregisteredNavBar.php';
} else {
    include 'reusable/NavBar.php';
}
?>
<div class="box-root flex-flex flex-direction--column" style="flex-grow: 1;">
    <div class="box-root padding-top--24 padding-bottom--24 flex-flex flex-direction--column"
         style="flex-grow: 1; z-index: 9;">
        <div class="formbg">
            <div class="padding-horizontal--48">
                <?php
                require_once '../dao/SurveyDAO.php';
                require_once '../dao/QuestionDAO.php';
                require_once '../model/Survey.php';
                require_once '../model/Question.php';
                $surveyDAO = new SurveyDAO();

                $currentSurveyID = $_GET['surveyID'];
                $currentSurvey = $surveyDAO->findById($currentSurveyID);

                if ((empty($currentSurvey) || $currentSurvey->isPublished() == Survey::NOT_PUBLISHED)) {
                    header("Location: Home.php");
                    exit();
                }
                $questions = $currentSurvey->getQuestions();
                ?>
                <span class="padding-bottom--24 flex-flex flex-justifyContent--center">
                    <?php echo $currentSurvey->getTitle() ?></span>
                <form method="POST" autocomplete="off">
                    <hr>
                    <?php
                    foreach ($questions as $question) {
                        ?>
                        <div>
                            <div class="field padding-bottom--15 padding-top--15">
                                <label for="question" style="width: 100%"><?php echo $question->getDescription() ?></label>
                                    <?php
                                    switch ($question->getType()) {
                                        case Question::TYPE_TEXT:
                                            ?>
                                            <input type="text"
                                                   name="answer[<?php echo $question->getId() ?>]" required>
                                            <?php
                                            break;
                                        case Question::TYPE_NUMBER:
                                            ?>
                                            <input type="number" style="width: 100%"
                                                   name="answer[<?php echo $question->getId() ?>]" required>
                                            <?php
                                            break;
                                        case Question::TYPE_DATE:
                                            ?>
                                            <input type="date" style="width: 100%"
                                                   name="answer[<?php echo $question->getId() ?>]" required>
                                            <?php
                                            break;
                                    }
                                    ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="field padding-top--64">
                        <input type="submit" name="save" value="Complete">
                        <input type="reset" name="cancel" id="cancel" style="margin-left: 10px;" value="Cancel">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    document.getElementById("cancel").onclick = function () {
        window.location.href = 'Home.php';
    };
</script>

<?php
require_once '../controller/EntryController.php';
require_once '../controller/AnswerController.php';

if (isset($_POST["save"])) {
    if (isset($_POST['answer'])) {

//            Saving Entry
        $entryController = new EntryController();
        $entrySavingResult = $entryController->handleRequests();

        if ($entrySavingResult == EntryController::ENTRY_ACTION_FAIL) {
            echo "<script>window.alert('You have completed the survey once.');</script>";
            exit();
        }

//        Saving Answers
        $answerController = new AnswerController();
        $answerSavingResult = $answerController->handleRequests($entrySavingResult);

        if ($answerSavingResult == AnswerController::ANSWER_ACTION_SUCCESS
        ) {
            echo "<script>window.alert('Entry Saved!');</script>";
            echo "<script>window.location.href='Home.php';</script>";
        }
        exit();
    }
}

?>
</body>
</html>