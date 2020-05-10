<?php
    include_once("includes/db/index.php");
    include_once("includes/db/user.php");

    if($_POST["login"] != "register") {
        header('Location: index.php');
        exit();
    }
    if(!empty($_POST["email"]) && !empty($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $pdo = getConnection();

            if(checkUserAvailability($email, $pdo)) {
                if(!empty($_POST["pseudo"])) {
                    $pseudo = $_POST["pseudo"];
                } else {
                    $pseudo = ucfirst(preg_replace('/\d+/', '', explode("@", $email)[0]));
                }
    
                register($email, md5($password), $pseudo, $pdo);
                $user = getUser($email, md5($password), $pdo);
                closeConnexion($pdo);
                
                $_SESSION["user"] = $user;
    
                header('Location: index.php');
                exit();
            } else {
                $emailErr = "Email already used, please sign in";
                closeConnexion($pdo);
            }
        } else {
            $emailErr = "Invalid email format";
        }
    }
?>

<div class="container main-content">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signin my-5">
                <div class="card-body">
                    <h5 class="card-title text-center">Register</h5>
                    <form class="form-signin" method="post"
                            oninput="/*confirm.setCustomValidity(confirm.value != password.value ? 'Passwords do not match.' : '')*/">
                        <input type="hidden" name="login" value="register"/>
                        <div class="form-label-group">
                            <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                            <label for="inputEmail">Email address</label>
                        </div>

                        <?php
                            if(isset($emailErr)) {
                                echo "<p class='text-danger'>$emailErr</p>";
                            }
                        ?>

                        <div class="form-label-group">
                            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
                            <label for="inputPassword">Password</label>
                        </div>
                        <!--<div class="form-label-group">
                            <input type="password" name="confirm" id="inputPasswordConfirm" class="form-control" placeholder="Password Confirm" required>
                            <label for="inputPasswordConfirm">Password Confirm</label>
                        </div>-->

                        <div class="form-label-group">
                            <input type="text" name="pseudo" id="inputPseudo" class="form-control" placeholder="Pseudo">
                            <label for="inputPseudo">Pseudo</label>
                        </div>

                        <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Register</button>
                        <hr class="my-4">
                        <a href="javascript:{}" onclick="$('input[name=login]').val('signin'); $('form').submit();">Already a member ? Sign in !</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>