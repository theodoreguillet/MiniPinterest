<?php
include_once("includes/db/index.php");
include_once("includes/db/picture.php");

$limit = 100;
$pdo = getConnection();

if(isset($PICTURES_THEME_ID)) {
    $pictures = getPicturesByThemeId($pdo, $PICTURES_THEME_ID, $limit);
} else if(isset($PICTURES_USER_ID)) {
    $pictures = getPicturesByUserId($pdo, $PICTURES_USER_ID, $limit);
} else if(isset($PICTURES_THEME_NAME)) {
    $pictures = getPicturesByThemeName($pdo, $PICTURES_THEME_NAME, $limit);
} else {
    $pictures = getPopularPictures($pdo, $limit);
}

closeConnexion($pdo);

?>

<link rel="stylesheet" href="styles/picture-grid.css">
<div class="container-fluid">
    <div class="grid flex-container wrap" data-masonry='{ "itemSelector": ".grid-item", "columnWidth": 250 }'>
    <?php
        foreach($pictures as $picture) {
            echo '
            <div class="grid-item pictureItem">
                <a href="picture.php?id='.$picture["id"].'" class="d-block mb-4 h-100">
                    <div class="pictureContainer">
                        <img class="picture" src="'.$picture["file"].'" alt="'.$picture["title"].'">
                    </div>
                </a>
            </div>
            ';
        }
        if(empty($pictures) && isset($PICTURES_USER_ID) && $PICTURES_USER_ID == $USER_ID) {
            echo '
            <div class="grid-item pictureItem">
                <a href="upload.php" class="d-block mb-4 h-100">
                    <div class="uploadButton"></div>
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

<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>
