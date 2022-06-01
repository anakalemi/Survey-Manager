<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Survey Manager</title>
    <link rel="stylesheet" type="text/css" href="../asset/css/home.css">
    <link rel="stylesheet" type="text/css" href="../asset/css/nav-bar.css">
</head>

<body>
<?php include 'reusable/UnregisteredNavBar.php' ?>

<div class="box-root flex-flex flex-direction--column" style="flex-grow: 1;">
    <div class="box-root padding-bottom--24 padding-top--24 flex-flex flex-direction--column"
         style="flex-grow: 1; z-index: 9;">

        <div class="formbg">
            <div class="padding-horizontal--48">

                <?php
                require_once '../dao/SurveyDAO.php';
                require_once '../dao/UserDAO.php';
                require_once '../model/Survey.php';
                require_once '../model/User.php';

                $surveyDAO = new SurveyDAO();
                $userDAO = new UserDAO();
                $allSurveys = $surveyDAO->findAll();

                foreach ($allSurveys as $survey) {
                    $author = $userDAO->findById($survey->getUserId());
                    if ($survey->isPublished() == Survey::PUBLISHED) {
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
</body>
</html>