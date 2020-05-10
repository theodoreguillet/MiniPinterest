<div class="row">
    <div class="col">
        <div class="row">
            <h1><?= $theme["name"] ?></h1>
        </div>
        <?php
        if($userCanModify($PROFILE_USER_ID)) {
            echo '
            <div class="row d-flex justify-content-left">
                <div class="flex-nav">
                    <button type="button" class="nav-icon" data-toggle="modal" data-target="#edit-theme">
                        <i class="fa fa-pencil fa-lg"></i>
                    </button>
                    <button type="button" class="nav-icon" id="removeBtn" data-toggle="modal" data-target="#confirm-remove">
                        <i class="fa fa-trash fa-lg"></i>
                    </button>
                </div>
            </div>
            ';
        }
        ?>
    </div>
    <div class="col">
        <div class="pull-right">
            <a class="user-mini" href="profile.php?user=<?= $user["id"] ?>">
                <h5><?= $user["pseudo"] ?></h5>
                <img class="user-image" src="<?= $user['image'] ? $user['image'] : 'assets/img/user.svg' ?>"></img>
            </a>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-theme" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <link rel="stylesheet" href="styles/form.css">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" name="editform" enctype="multipart/form-data">
                <input type="hidden" name="theme" value="<?= $theme["id"] ?>">
                <div class=" font-weight-bold modal-header">
                    Edit Theme
                </div>
                <div class="modal-body">
                    <div class="form-label-group">
                        <input type="text" name="name" id="themeName" class="form-control" placeholder="Name" value="<?= $theme["name"] ?>" required autofocus>
                        <label for="themeName">Name</label>
                    </div>
                    <?php if (!empty($editThemeErr)) {
                        echo "<div style='font-weight:bold;color:red;margin:4px;'>$editThemeErr</div>";
                    } ?>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <input class="btn btn-danger" name="edit-theme" type="submit" value="Change" />
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-remove" tabindex="-1" role="dialog" aria-labelledby="confirm" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="removeForm">
            <input type="hidden" name="theme" value="<?= $theme["id"] ?>">
            <div class="modal-content">
                <div class=" font-weight-bold modal-header">
                    Confirm Remove
                </div>
                <div class="modal-body">
                    Are you sure you want to remove this theme ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <input class="btn btn-danger" name="remove" type="submit" value="Remove Theme" />
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    if(<?= boolstr(!empty($editThemeErr)) ?>) {
        $("#edit-theme").modal("show");
    }
</script>