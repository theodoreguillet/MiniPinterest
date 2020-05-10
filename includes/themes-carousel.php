<?php

include_once("includes/db/index.php");
include_once("includes/db/theme.php");
include_once("includes/db/picture.php");

$limit = 10;
$pdo = getConnection();

$themes = array();

$popularThemes = getPopularThemes($pdo, $limit);
foreach($popularThemes as $themeName) {
    $pictures = getPicturesByThemeName($pdo, $themeName, 1);
    $files = array_column($pictures, "file");
    if(!empty($files)) {
        array_push($themes, array(
            "name" => $themeName,
            "pictures" => $files,
            "link" => "index.php?theme=$themeName"
        ));
    }
}

?>
<link rel="stylesheet" href="styles/themes-carousel.css">
<div class="categories-slider">
    <div id="carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner w-100 mx-auto" role="listbox">
        <?php
        $i = 0;
        foreach($themes as $theme) {
            echo '
            <div class="carousel-item '.($i == 0 ? "active" : "").'">
                <div class="carousel-item-inner">
                    <a href="'.$theme["link"].'">
                        <img src="'.$theme["pictures"][0].'" class="carousel-image" alt="'.$theme["name"].'">
                        <div class="carousel-image-title">
                            <h5>'.$theme["name"].'</h5>
                        </div>
                    </a>
                </div>
            </div>
            ';
            $i++;
        }
        while($i < 6) {
            echo '
            <div class="carousel-item '.($i == 0 ? "active" : "").'">
                <div class="carousel-item-inner">
                    <a href="theme-new.php">
                        <div class="carousel-add-theme"></div>
                        <div class="carousel-image-title">
                            <h5>New Theme</h5>
                        </div>
                    </a>
                </div>
            </div>
            ';
            $i++;
        }
        ?>
        </div>
        <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
            <div class="carousel-control-icon">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </div>
        </a>
        <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
            <div class="carousel-control-icon">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </div>
        </a>
    </div>
</div>
<script>
    $('#carousel').on('slide.bs.carousel', function (e) {
        var $e = $(e.relatedTarget);
        var idx = $e.index();
        var itemsPerSlide = 5;
        var totalItems = $('.carousel-item').length;
    
        if (idx >= totalItems-(itemsPerSlide-1)) {
            var it = itemsPerSlide - (totalItems - idx);
            for (var i=0; i<it; i++) {
                // append slides to end
                if (e.direction=="left") {
                    $('.carousel-item').eq(i).appendTo('.carousel-inner');
                }
                else {
                    $('.carousel-item').eq(0).appendTo('.carousel-inner');
                }
            }
        }
    });
</script>