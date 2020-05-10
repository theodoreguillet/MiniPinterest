<?php
include("includes/a_config.php");
include("includes/db/index.php");
include("includes/db/user.php");
include("includes/db/theme.php");
include("includes/db/picture.php");

if(isset($_POST["remove"])) {
    $PICTURE_ID = $_POST["remove"];
} else if(isset($_GET["id"])) {
    $PICTURE_ID = $_GET["id"];
} else {
    $PICTURE_ID = $_POST["id"];
}

if (!$USER_LOGGED || !isset($PICTURE_ID)) {
    header('Location: index.php');
    exit();
}

$pdo = getConnection();
$picture = getPictureById($pdo, $PICTURE_ID);
$theme = $picture ? getThemeById($pdo, $picture["themeId"]) : false;
$user = $theme ? getUserById($pdo, $theme["userId"]) : false;
closeConnexion($pdo);

if(!$picture || !$theme || !$user) {
    header('Location: index.php');
    exit();    
}

if(isset($_POST["remove"]) && $userCanModify($user["id"])) {
    $pdo = getConnection();

    unlink($picture["file"]);
    removePicture($pdo, $picture["id"]);
    
    closeConnexion($pdo);

    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <?php include("includes/head-tag-contents.php"); ?>
    <link rel="stylesheet" href="styles/picture.css">
</head>

<body class="bg-light">
    <?php include("includes/navigation.php"); ?>

    <div class="container card bg-white" id="main-content">
        <div class="row">
            <div class="col-6">
                <div class="pictureContainer">
                    <img class="picture" src="<?= $picture["file"] ?>"></img>
                </div>
            </div>
            <div class="col-6">
                <div class="dropdown pull-right">
					<a class="btn" href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-ellipsis-h fa-lg"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="profile.php?user=<?= $user["id"] ?>&theme=<?= $theme["id"] ?>">Author</a>
                        <?php
                        if($userCanModify($user["id"])) {
                            // <input class="dropdown-item" id="removeSubmit" name="remove" type="submit" value="Remove">
                            echo '
                            <div class="dropdown-divider"></div>
                            <form method="post" id="removeForm">
                                <input type="hidden" name="remove" value="'.$picture["id"].'">
                                <input type="button" name="btn" value="Remove" id="removeBtn" data-toggle="modal" data-target="#confirm-remove" class="dropdown-item" />
                            </form>
                            ';
                        }
                        ?>
					</div>
                </div>
                <h2 class="card-title text-center mt-2 mb-4"><?= $picture["title"] ?></h2>
                <!-- <hr class="my-4"> -->
                <p><?= $picture["description"] ?></p>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm-remove" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="font-weight-bold modal-header">
                    Confirm Remove
                </div>
                <div class="modal-body">
                    Are you sure you want to remove this picture ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a href="#" id="removeConfirm" class="btn btn-danger success">Remove</a>
                </div>
            </div>
        </div>
    </div>

    <?php include("includes/footer.php"); ?>
</body>

<script>
    $('#removeBtn').click(function() {
     /* when the button in the form, display the entered values in the modal */
     $('#lname').text($('#lastname').val());
     $('#fname').text($('#firstname').val());
    });

    $('#removeConfirm').click(function(){
        $('#removeForm').submit();
    });
</script>

</html>