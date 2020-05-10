<?php

include_once("includes/db/index.php");
include_once("includes/db/theme.php");
include_once("includes/db/picture.php");

$limit = 100;
$pdo = getConnection();
/*
if(!isset($THEME_USER_ID)) {
    error_log("THEME_USER_ID not defined");
    exit();
}
$themes = getUserThemes($pdo, $THEME_USER_ID, $limit);
*/

$themes = array();
if(isset($THEME_USER_ID)) {
    $userThemes = getUserThemes($pdo, $THEME_USER_ID, $limit);
    foreach($userThemes as $t) {
        $pictures = getPicturesByThemeId($pdo, $t["themeId"], 5);
        if(!empty($pictures) || $userCanModify($THEME_USER_ID)) {
            array_push($themes, array(
                "name" => $t["themeName"],
                "pictures" => array_column($pictures, "file"),
                "link" => "profile.php?user=".$t["userId"]."&theme=".$t["themeId"]
            ));
        }
    }
} else {
    $popularThemes = getPopularThemes($pdo, $limit);
    foreach($popularThemes as $themeName) {
        $pictures = getPicturesByThemeName($pdo, $themeName, 5);
        if(!empty($pictures)) {
            array_push($themes, array(
                "name" => $themeName,
                "pictures" => array_column($pictures, "file"),
                "link" => "index.php?theme=$themeName"
            ));
        }
    }
}

closeConnexion($pdo);

?>

<link rel="stylesheet" href="styles/themes-grid.css">
<div class="container-fluid">
    <div class="flex-container wrap align-center">
    <?php
        foreach($themes as $theme) {
            echo '
            <div class="themeItem">
                <a href="'.$theme["link"].'" class="d-block mb-4 h-100">
                    <div class="themeContainer">';
            if(!empty($theme["pictures"])) {
                echo '  <img class="themeImage" src="'.$theme["pictures"][0].'" alt="'.$theme["name"].'">';
            }
            echo '      <div class="themeName">
                            <h5>'.$theme["name"].'</h5>
                        </div>
                    </div>
                </a>
            </div>
            ';
        }
        if(isset($THEME_USER_ID) && $THEME_USER_ID == $USER_ID) {
            echo '
            <div class="themeItem">
                <a href="theme-new.php" class="d-block mb-4 h-100">
                    <div class="newThemeButton"></div>
                </a>
            </div>
            ';
        }
        for($i = 0; $i < 12; $i++) {
            echo '<div class="paddingItem"></div>'; // Padding
        }
    ?>
    </div>
</div>