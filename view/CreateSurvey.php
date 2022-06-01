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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../asset/css/home.css">
    <link rel="stylesheet" type="text/css" href="../asset/css/nav-bar.css">
    <link rel="stylesheet" type="text/css" href="../asset/css/createSurvey.css">
</head>

<body>
<?php include 'reusable/NavBar.php' ?>

<div class="box-root flex-flex flex-direction--column" style="flex-grow: 1;">
    <div class="box-root padding-top--24 padding-bottom--24 flex-flex flex-direction--column"
         style="flex-grow: 1; z-index: 9;">
        <div class="formbg">
            <div class="padding-horizontal--48">
                <form method="POST" autocomplete="off">
                    <div class="field padding-bottom--24">
                        <label for="title"><b>Survey Title</b></label>
                        <input type="text" name="title" id="title" autofocus required>
                    </div>
                    <hr>
                    <div>
                        <div class="field padding-bottom--15 padding-top--15">
                            <label for="question">Question</label>
                            <input type="text" name="question[0]" id="question" required>
                        </div>
                        <div class="field padding-bottom--15">
                            <label for="aType">Answer Type</label>
                            <select id="aType" name="aType[0]">
                                <option value="0">Text</option>
                                <option value="1">Number</option>
                                <option value="2">Date</option>
                            </select>
                        </div>
                        <hr>
                    </div>
                    <div id="newQ"></div>
                    <div class="field padding-bottom--15 padding-top--15" style="text-align: right">
                        <input type="button" name="create" value="Add Question" id="addQuestion">
                    </div>
                    <div class="field padding-top--64">
                        <input type="submit" name="save" value="Save Survey">
                        <input type="reset" name="cancel" id="cancel" style="margin-left: 10px;" value="Cancel">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    let questionsIds = [];
    let i = 1;

    // add row
    $("#addQuestion").click(function () {
        var html = '';
        html += '<div id="inputFormQuestion[' + i + ']">';
        html += '<div class="field padding-bottom--15 padding-top--15">';
        html += '<label for="question[' + i + ']">Question</label>';
        html += '<input type="text" name="question[' + i + ']" id="question">';
        html += '</div>';
        html += '<div class="field padding-bottom--15">';
        html += '<label for="aType">Answer Type</label>';
        html += '<select id="aType" name="aType[' + i + ']"">';
        html += '<option value="0">Text</option>';
        html += '<option value="1">Number</option>';
        html += '<option value="2">Date</option>';
        html += '</select>';
        html += '<div class="field padding-top--15" style="text-align: right;">';
        html += '<input type="button" name="remove" value="Remove" id="removeQuestion[' + i + ']" onclick="reply_click(this.id)">';
        html += '</div>';
        html += '</div>';
        html += '<hr>';
        html += '</div>';

        $('#newQ').append(html);
        questionsIds.push(i);
        i++;
    });

    function reply_click(clicked_id) {
        let id = clicked_id.substring(
            clicked_id.indexOf("[") + 1,
            clicked_id.lastIndexOf("]")
        );
        questionsIds = removeItem(questionsIds, id);

        id = 'inputFormQuestion[' + id + ']';
        let elementToRemove = document.getElementById(id);
        elementToRemove.parentNode.removeChild(elementToRemove);
    }

    function removeItem(arr, item) {
        let index = arr.findIndex(i => i == item);
        if (index > -1) {
            arr.splice(index, 1);
        }
        return arr;
    }

    document.getElementById("cancel").onclick = function () {
        window.location.href = 'Home.php';
    };

</script>

<?php
require_once '../controller/SurveyController.php';
require_once '../controller/QuestionController.php';

if (isset($_POST["save"])) {
    if (isset($_POST['title']) && isset($_POST['question'])) {
//            Saving Survey
        $surveyController = new SurveyController();
        $surveySavingResult = $surveyController->handleRequests();

        if ($surveySavingResult == SurveyController::SURVEY_ACTION_FAIL_DUE_TITLE_DUB) {
            echo "<script>window.alert('This title has already been used.');</script>";
            exit();
        }

//        Saving Questions
        $questionController = new QuestionController();
        $questionSavingResult = $questionController->handleRequests();

        if ($surveySavingResult == SurveyController::SURVEY_ACTION_SUCCESS
            && $questionSavingResult == QuestionController::QUESTION_ACTION_SUCCESS
        ) {
            echo "<script>window.alert('Survey Saved!');</script>";
            echo "<script>window.location.href='Home.php';</script>";
        }
        exit();
    }
}
?>

</body>
</html>