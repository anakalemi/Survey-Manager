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
            <h1><?php echo 'Welcome ' . $currentUser->getName() . '!'; ?></h1>
        </div>
        <div class="formbg">
            <div class="padding-horizontal--48">
                <div class="field padding-bottom--24" style="text-align: right">
                    <input type="button" id="createNewButton" value="Create New">
                </div>
                <span class="padding-bottom--24 flex-flex flex-justifyContent--center">Your Surveys</span>
                <?php
                require_once '../dao/SurveyDAO.php';
                require_once '../dao/UserDAO.php';
                require_once '../model/Survey.php';
                require_once '../model/User.php';

                $surveyDAO = new SurveyDAO();
                $userDAO = new UserDAO();
                $allSurveys = $surveyDAO->findAll();
                $surveys = $surveyDAO->getSurveysByUserId($currentUser->getUserId());

                foreach ($surveys as $survey) {
                    if ($survey->isPublished() == Survey::NOT_PUBLISHED) {

                        ?>
                        <div class="list padding-bottom--15">
                            <form method="GET" action="EditSurvey.php">
                                <input hidden type="hidden" name="surveyID" value="<?php echo $survey->getId() ?>">
                                <button>
                                    <span class="span-before"><?php echo $survey->getTitle() ?></span>
                                    <span class="span-after">Edit</span>
                                </button>
                            </form>
                        </div>
                        <?php
                    }
                } ?>
                <span class="padding-bottom--24">Published</span>
                <?php
                foreach ($surveys as $survey) {
                    if ($survey->isPublished() == Survey::PUBLISHED) {
                        ?>
                        <div class="list padding-bottom--15">
                            <form method="GET" action="Statistics.php">
                                <input hidden type="hidden" name="surveyID" value="<?php echo $survey->getId() ?>">
                                <button>
                                    <span class="span-before"><?php echo $survey->getTitle() ?></span>
                                    <span class="span-after">View Statistics</span>
                                </button>
                            </form>
                        </div>
                        <?php
                    }
                }
                ?>
                <span class="padding-bottom--24 padding-top--64 flex-flex flex-justifyContent--center">
                    Other's Surveys</span>
                <?php
                foreach ($allSurveys as $survey) {
                    $author = $userDAO->findById($survey->getUserId());
                    if ($author->getUserId() != $currentUser->getUserId()
                        && $survey->isPublished() == Survey::PUBLISHED) {
                        ?>
                        <div class="list padding-bottom--15">
                            <form method="GET" action="FillSurvey.php">
                                <input hidden type="hidden" name="surveyID" value="<?php echo $survey->getId() ?>">
                                <button>
                                    <span class="span-before">
                                        <?php echo $survey->getTitle() . ' | Author: ' . $author->getName() ?>
                                    </span>
                                    <span class="span-after">Fill Survey</span>
                                </button>
                            </form>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById("createNewButton").onclick = function () {
        window.location.href = 'CreateSurvey.php';
    };
</script>
</body>
</html>