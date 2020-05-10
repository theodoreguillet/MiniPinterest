<link rel="stylesheet" href="styles/form.css">
<div class="hider">
    <?php 
        if(isset($_POST["login"]) && $_POST["login"] == "signin") {
            include("includes/login/signin.php");
        } else if(isset($_POST["login"]) && $_POST["login"] == "register") {
            include("includes/login/register.php");
        } else {
            include("includes/login/choice.php");
        }
    ?>
</div>