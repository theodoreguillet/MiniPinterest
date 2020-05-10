<div class="row">
    <div class="col">
        <div class="row">
            <h1><?= $user["pseudo"] ?></h1>
        </div>
        <div class="row">
            <p>Total posts: <span style="color:gray"><?= $totalPosts ?></span></p>
        </div>
        <div class="row d-flex justify-content-left">
            <form method="post">
                <div class="flex-nav">
                        <input 
                            class="<?= isset($_POST['pictures']) ? 'nav-btn' : 'nav-btn-active' ?>" 
                            type="submit" name="themes" value="Themes">
                        <input 
                            class="<?= isset($_POST['pictures']) ? 'nav-btn-active' : 'nav-btn' ?>" 
                            type="submit" name="pictures" value="Pictures">
                </div>
            </form>
        </div>
    </div>
    <div class="col">
        <div class="pull-right">
            <div class="row d-flex justify-content-center">
                <img class="user-image" src="<?= $user['image'] ? $user['image'] : 'assets/img/user.svg' ?>"></img>
                <?php 
                if($userCanModify($PROFILE_USER_ID)) {
                    echo '
                    <form class="hidden" method="post" id="set-image-form" name="set-image" enctype="multipart/form-data">
                        <input type="hidden" name="user" value="'.$user["id"].'">
                        <input type="hidden" name="set-image" value="">
                        <input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_IMAGE_SIZE ?>" />
                        <input type="file" id="upload-user-image" class="hidden" name="image" multiple accept="image/*">
                    </form>
                    ';
                }
                ?>
            </div>
            <?php
            if($userCanModify($PROFILE_USER_ID)) {
                echo '
                <div class="row d-flex justify-content-center">
                    <div class="flex-nav">
                        <button type="button" class="nav-icon" data-toggle="modal" data-target="#edit-pseudo">
                            <i class="fa fa-pencil fa-lg"></i>
                        </button>
                ';
                if($PROFILE_USER_ID == $USER_ID) {
                    echo '
                        <button type="button" class="nav-icon" data-toggle="modal" data-target="#edit-password">
                            <i class="fa fa-lock fa-lg"></i>
                        </button>
                    ';
                }
                echo '
                        <button type="button" class="nav-icon" data-toggle="modal" data-target="#confirm-remove-user">
                            <i class="fa fa-trash fa-lg"></i>
                        </button>
                    </div>
                </div>
                ';
            }
            ?>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-pseudo" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <link rel="stylesheet" href="styles/form.css">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" name="setpseudoform" enctype="multipart/form-data">
                <input type="hidden" name="user" value="<?= $user["id"] ?>">
                <div class=" font-weight-bold modal-header">
                    Edit Pseudo
                </div>
                <div class="modal-body">
                    <div class="form-label-group">
                        <input type="text" name="pseudo" id="pseudo" class="form-control" placeholder="Pseudo" value="<?= $user["pseudo"] ?>" required autofocus>
                        <label for="pseudo">Pseudo</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <input class="btn btn-danger" name="set-pseudo" type="submit" value="Change" />
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-password" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <link rel="stylesheet" href="styles/form.css">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" name="setpasswordform" enctype="multipart/form-data"
                    oninput="passconfirm.setCustomValidity(passconfirm.value != password.value ? 'Passwords do not match.' : '')">
                <input type="hidden" name="user" value="<?= $user["id"] ?>">
                <div class=" font-weight-bold modal-header">
                    Change Password
                </div>
                <div class="modal-body">
                        <div class="form-label-group">
                            <input type="password" name="current" id="currentPassword" class="form-control" placeholder="Current Password" required>
                            <label for="currentPassword">Current Password</label>
                        </div>
                        <div class="form-label-group">
                            <input type="password" name="password" id="newPassword" class="form-control" placeholder="New Password" required>
                            <label for="newPassword">New Password</label>
                        </div>
                        <div class="form-label-group">
                            <input type="password" name="passconfirm" id="newPasswordConfirm" class="form-control" placeholder="New Password Confirm">
                            <label for="newPasswordConfirm">New Password Confirm</label>
                        </div>
                    <?php if (!empty($setPasswordErr)) {
                        echo "<div style='font-weight:bold;color:red;margin:4px;'>$setPasswordErr</div>";
                    } ?>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <input class="btn btn-danger" id="setPassword" name="set-password" type="submit" value="Change" />
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-remove-user" tabindex="-1" role="dialog" aria-labelledby="confirm" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="removeForm">
            <input type="hidden" name="user" value="<?= $user["id"] ?>">
            <div class="modal-content">
                <div class=" font-weight-bold modal-header">
                    Confirm Remove
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to remove your account ?</p>
                    <p class="text-danger font-weight-bold">All your pictures and themes will be lost...</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <input class="btn btn-danger" name="remove-user" type="submit" value="Remove my account" />
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    if(<?= boolstr($USER_ID == $PROFILE_USER_ID) ?>) {
        $(".user-image").click(function() {
            console.log("CLICK");
            $("#upload-user-image").trigger("click");
        });
        $("#upload-user-image").change(function() {
            $("#set-image-form").submit();
        });
    }

    if(<?= boolstr(!empty($setPasswordErr)) ?>) {
        $("#edit-password").modal("show");
    }
</script>