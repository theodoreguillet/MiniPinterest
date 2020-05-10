<?php
include("includes/a_config.php");
include("includes/db/index.php");
include("includes/db/theme.php");

if (!$USER_LOGGED) {
    header('Location: index.php');
    exit();
}

if(isset($_POST["name"])) {
    $pdo = getConnection();
    if(checkThemeAvailability($pdo, $_POST["name"], $USER_ID)) {
        addTheme($pdo, $_POST["name"], $USER_ID);
    } else {
        $error = "Theme already created";
    }
    $theme = findUserThemeByName($pdo, $USER_ID, $_POST["name"]);
    closeConnexion($pdo);

    if($theme) {
        header('Location: profile.php?theme='.$theme["id"]);
        exit();
    } else {
        $error = "Unknow error";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <?php include("includes/head-tag-contents.php"); ?>
    <link rel="stylesheet" href="styles/form.css">
</head>

<body class="bg-light">
    <?php include("includes/navigation.php"); ?>

    <div class="container card bg-white" id="main-content">
        <h5 class="card-title text-center mb-4">New Theme</h5>
        <form method="post" name="uploadform" enctype="multipart/form-data">
            <div class="form-label-group">
                <input type="text" name="name" id="themeName" class="form-control" placeholder="Name" required autofocus>
                <label for="themeName">Name</label>
            </div>
            <div class="form-group" style="float:right">
                <input class="btn btn-danger" name="upload" type="submit" value="Create" />
            </div>
            <?php if (!empty($error)) {
                echo "<div style='font-weight:bold;color:red;margin:4px;'>$error</div>";
            } ?>
        </form>
    </div>


    <?php include("includes/footer.php"); ?>
</body>

</html>