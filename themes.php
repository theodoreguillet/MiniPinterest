<?php include("includes/a_config.php"); ?>
<!DOCTYPE html>
<html>

<head>
    <?php include("includes/head-tag-contents.php"); ?>
</head>
<?php 
/*
    include("includes/db/index.php");
    include("includes/db/theme.php");
    include("includes/db/picture.php");
    $pdo = getConnection();
    // print_r(getPopularThemes($pdo));
    print_r(getPicturesByTheme($pdo, "Maison")[0]);
    closeConnexion($pdo);
*/
?>
<body>
    <?php include("includes/navigation.php"); ?>

    <div class="mainContainer">
        <?php include("includes/themes-grid.php"); ?>
    </div>

    <?php include("includes/footer.php"); ?>

</body>

</html>