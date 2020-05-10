<?php 
include("includes/a_config.php");

if(isset($_GET["theme"])) {
    $PICTURES_THEME_NAME = $_GET["theme"];
} else if(isset($_POST["theme"])) {
    $PICTURES_THEME_NAME = $_POST["theme"];
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php include("includes/head-tag-contents.php"); ?>
</head>

<body>
    <?php 
        if($USER_LOGGED) {
            include("includes/navigation.php");
            if(!isset($PICTURES_THEME_NAME)) {
                include("includes/themes-carousel.php");
            }
        } else {
            include("includes/login/index.php");
        }
    ?>

    <div class="mainContainer">
        <?php include("includes/picture-grid.php"); ?>
    </div>

    <?php 
        include("includes/footer.php");
    ?>
</body>

</html>