<?php
include("includes/a_config.php");
include("includes/db/index.php");
include("includes/db/theme.php");
include("includes/db/picture.php");
include("includes/utils/file-upload.php");
include("includes/utils/uuid.php");

if (!$USER_LOGGED) {
    header('Location: index.php');
    exit();
}

// strrchr renvoie l'extension avec le point (« . »).
// substr(chaine,1) ignore le premier caractère de chaine.
// strtolower met l'extension en minuscules

if (
    isset($_POST['upload']) && 
    isset($_FILES['image']) && 
    !empty($_FILES['image']['name']) &&
    !empty($_POST['title']) &&
    !empty($_POST['theme'])
) {

    //Vérification générale des différentes erreurs possibles
    if ($_FILES['image']['error'] > 0) {
        $error = "Upload Error: ".codeToMessage($_FILES['image']['error']);
    
    } else if ($_FILES['image']['size'] > MAX_IMAGE_SIZE) {
        $error = "File is too large (100Ko maximum)";
    
    } else {
        $imageMime = checkImageType($_FILES['image']);
        $extension = false;
        if($imageMime) {
            $extension = mime2ext($imageMime);
        }
        if($imageMime && $extension) {
            // Stockage du fichier
            $uuid = UUID::v4();
            $path = 'storage/'.$uuid.'.'.$extension;
            if(!file_exists(__DIR__."/storage")) {
                mkdir(__DIR__."/storage");
            }
            $uploaded = move_uploaded_file($_FILES['image']['tmp_name'], __DIR__."/".$path);

            if($uploaded) {
                // Ajout dans la BDD
                $pdo = getConnection();

                $desc = "";
                if(isset($_POST['description'])) {
                    $desc = $_POST['description'];
                }

                $title = $_POST['title'];

                $theme = findUserThemeByName($pdo, $USER_ID, $_POST['theme']);
                if($theme) {
                    addPicture($pdo, $title, $path, $desc, $theme["id"]);
                    closeConnexion($pdo);

                    header('Location: profile.php?theme='.$theme["id"]);
                    exit();
                } else {
                    $error = "Bad theme";
                }

                closeConnexion($pdo);
            } else {
                $error = "File storage error";
            }
        } else {
            $error = "Bad file type: ".$imageMime;
        }
    }
}

$pdo = getConnection();

$themes = getUserThemes($pdo, $USER_ID);

closeConnexion($pdo);

?>

<!DOCTYPE html>
<html>

<head>
    <?php include("includes/head-tag-contents.php"); ?>
    <link rel="stylesheet" href="styles/form.css">
    <link rel="stylesheet" href="styles/upload.css">
</head>

<body class="bg-light">
    <?php include("includes/navigation.php"); ?>

    <div class="container card bg-white" id="main-content">
        <form method="post" name="uploadform" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_IMAGE_SIZE ?>" /> <!-- Taille maximale du fichier 1Mo -->
            <input type="file" id="upload-file" class="hidden" name="image" multiple accept="image/*"> <!-- Image upload par l'user -->
            <div class="row">
                <div class="col-4">
                    <div id="upload-main">
                        <div id="upload-area" class="outer" title="No file selected">
                            <div class="inner">
                            </div>
                        </div>
                        <div id="preview" class="hidden">
                            <img id="preview-image" scr="#" alt="preview"></img>
                            <div id="preview-trash" class="shadow-sm" ></div>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="description">
                        <div class="form-label-group">
                            <input type="text" name="title" id="title" class="form-control" placeholder="Title" required autofocus>
                            <label for="title">Title</label>
                        </div>
                        <hr class="my-4">
                        <!--
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input class="form-control" type="text" id="title" name="title" required autofocus></input>
                        </div>-->
                        <div class="form-group">
                            <label for="themeSelect">Theme</label>
                            <div class="form-row">
                                <div class="col-11">
                                    <select class="form-control" name="theme" id="themeSelect" required>
                                    <?php
                                        foreach($themes as $theme) {
                                            echo '<option>'.$theme["themeName"].'</option>';
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="col-1 my-auto mx-auto">
                                    <a class="btn btn-secondary btn-sm" href="theme-new.php" title="New Theme">
                                        <i class="fa fa-plus fa-lg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descriptionTextarea">Description</label>
                            <textarea class="form-control" id="descriptionTextarea" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group" style="float:right">
                            <input class="btn btn-danger" name="upload" type="submit" value="Upload" />
                        </div>
                        <?php if (!empty($error)) {
                            echo "<div style='font-weight:bold;color:red;margin:4px;'>$error</div>";
                        } ?>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <?php include("includes/footer.php"); ?>
</body>

<script>
    $("#upload-main").click(function() {
        $("#upload-file").trigger("click");
    });
    $("#upload-file").change(function(event) {
        // $('#result span').text(event.target.files[0].name);
        if (this.files && this.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $("#upload-area").addClass("hidden");
                $("#preview").removeClass("hidden");
                $('#preview-image').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]); // convert to base64 string
        }
    });
    $("#preview-trash").click(function(event) {
        event.stopPropagation();
        $("#upload-area").removeClass("hidden");
        $("#preview").addClass("hidden");
        $('#preview-image').attr('src', '');

        var input = $("#upload-file");
        input.replaceWith(input.val('').clone(true));
    });
    $('#upload-main').on('drag dragstart dragend dragover dragenter dragleave drop', function(event) {
            event.preventDefault();
            event.stopPropagation();
        })
        .on('dragover dragenter', function() {
            $(this).addClass('is-dragover');
        })
        .on('dragleave dragend drop', function() {
            $(this).removeClass('is-dragover');
        })
        .on('drop', function(event) {

            // No idea if this is the right way to do things
            $("#upload-file").prop('files', event.originalEvent.dataTransfer.files);
            $("#upload-file").trigger('change');
        });
</script>

</html>