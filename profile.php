<?php 
include("includes/a_config.php");
include("includes/db/index.php");
include("includes/db/user.php");
include("includes/db/theme.php");
include("includes/db/picture.php");
include("includes/utils/file-upload.php");
include("includes/utils/uuid.php");

if (!$USER_LOGGED) {
    header('Location: index.php');
    exit();
}

if(isset($_POST["user"])) {
    $PROFILE_USER_ID = $_POST["user"];
} else if(isset($_GET["user"])) {
    $PROFILE_USER_ID = $_GET["user"];
} else {
    $PROFILE_USER_ID = $USER_ID;
}

if(isset($_POST["theme"])) {
    $PROFILE_THEME_ID = $_POST["theme"];
} else if(isset($_GET["theme"])) {
    $PROFILE_THEME_ID = $_GET["theme"];
} else {
    $PROFILE_THEME_ID = null;
}

$pdo = getConnection();
$user = getUserById($pdo, $PROFILE_USER_ID);
if(isset($PROFILE_THEME_ID)) {
    $theme = getThemeById($pdo, $PROFILE_THEME_ID);
    if(!$theme) {
        $PROFILE_THEME_ID = null;
    }
}

if(!$user) {
    closeConnexion($pdo);
    header('Location: index.php');
    exit();
}

$totalPosts = totalUserPicturesFromId($pdo, $PROFILE_USER_ID);

if(isset($_POST["remove"]) && isset($theme) && $userCanModify($PROFILE_USER_ID)) {
    $files = getPictureFilesByThemeId($pdo, $theme["id"]);
    foreach($files as $f) {
        unlink(__DIR__."/".$f);
    }
    removePictureByThemeId($pdo, $theme["id"]);
    removeTheme($pdo, $theme["id"]);
    
    closeConnexion($pdo);
    header('Location: profile.php?user='.$PROFILE_USER_ID);
    exit();
}

if(isset($_POST["edit-theme"]) && isset($theme) && $userCanModify($PROFILE_USER_ID) && isset($_POST["name"])) {
    $newName = $_POST["name"];
    if(checkThemeAvailability($pdo, $newName, $PROFILE_USER_ID)) {
        changeThemeName($pdo, $theme["id"], $newName);

        closeConnexion($pdo);
        header('Location: '.$_SERVER['REQUEST_URI']);
        exit();
    } else {
        $editThemeErr = "Theme already exist";
    }
}

if(isset($_POST["set-pseudo"]) && $userCanModify($PROFILE_USER_ID)) {
    $newPseudo = $_POST["pseudo"];
    changeUserPseudo($pdo, $PROFILE_USER_ID, $newPseudo);

    closeConnexion($pdo);
    header('Location: '.$_SERVER['REQUEST_URI']);
    exit();
}

if(isset($_POST["set-password"]) && $PROFILE_USER_ID == $USER_ID && isset($_POST["current"]) && isset($_POST["password"])) {
    $currentPassword = md5($_POST["current"]);
    $newPassword = md5($_POST["password"]);

    if($user["pwd"] == $currentPassword) {
        changeUserPassword($pdo, $PROFILE_USER_ID, $newPassword);

        closeConnexion($pdo);
        header('Location: '.$_SERVER['REQUEST_URI']);
        exit();
    } else {
        $setPasswordErr = "Wrong password";
    }
}

if(isset($_POST["set-image"]) && $userCanModify($PROFILE_USER_ID) && 
    isset($_FILES['image']) && $_FILES['image']['error'] == 0 && $_FILES['image']['size'] < MAX_IMAGE_SIZE
) {
    $imageMime = checkImageType($_FILES['image']);
    if($imageMime) {
        $extension = mime2ext($imageMime);
        if($extension) {
            // Stockage du fichier
            $uuid = UUID::v4();
            $path = 'storage/'.$uuid.'.'.$extension;
            if(!file_exists(__DIR__."/storage")) {
                mkdir(__DIR__."/storage");
            }
            $uploaded = move_uploaded_file($_FILES['image']['tmp_name'], __DIR__."/".$path);

            if($uploaded) {
                changeUserImage($pdo, $PROFILE_USER_ID, $path);
                $_SESSION["user"]["image"] = $path;
            }
        }
    }

    closeConnexion($pdo);
    header('Location: '.$_SERVER['REQUEST_URI']);
    exit();
}

if(isset($_POST["remove-user"]) && $userCanModify($PROFILE_USER_ID)) {
    $files = getPictureFilesByUserId($pdo, $PROFILE_USER_ID);
    foreach($files as $f) {
        unlink(__DIR__."/".$f);
    }
    if($user["image"]) {
        unlink(__DIR__."/".$user["image"]);
    }
    removePictureByUserId($pdo, $PROFILE_USER_ID);
    removeThemeByUserId($pdo, $PROFILE_USER_ID);
    removeUser($pdo, $PROFILE_USER_ID);
    
    closeConnexion($pdo);
    unset($_SESSION["user"]);
    header('Location: index.php');
    exit();
}

closeConnexion($pdo);

$THEME_USER_ID = $PROFILE_USER_ID;
$PICTURES_USER_ID = $PROFILE_USER_ID;
$PICTURES_THEME_ID = $PROFILE_THEME_ID;

?>
<!DOCTYPE html>
<html>

<head>
    <?php include("includes/head-tag-contents.php"); ?>
    <link rel="stylesheet" href="styles/profile.css">
</head>

<body>
    <?php include("includes/navigation.php"); ?>

    <div class="mainContainer">
        <div class="card profile-container">
            <?php 
            if(isset($PROFILE_THEME_ID)) {
                include("includes/profile/theme.php");
            } else {
                include("includes/profile/index.php");
            }
            ?>
        </div>

        <?php
        if(isset($PROFILE_THEME_ID) || isset($_POST['pictures'])) {
            include("includes/picture-grid.php");
        } else {
            include("includes/themes-grid.php");
        }
        ?>
    </div>

    <?php include("includes/footer.php"); ?>

</body>

</html>