<?php
    include_once("includes/db/index.php");
    include_once("includes/db/user.php");

    if($_POST["login"] != "signin") {
        header('Location: index.php');
        exit();
    }
    if(!empty($_POST["email"]) && !empty($_POST["password"])) {
        if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $pdo = getConnection();
            $user = getUser($_POST["email"], md5($_POST["password"]), $pdo);
            closeConnexion($pdo);

            if($user) {
                $_SESSION["user"] = $user;
                header('Location: index.php');
                exit();
            } else {
                $error = "Invalid email or password";
            }
        } else {
            $error = "Invalid email format";
        }
    }
?>

<div class="container main-content">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signin my-5">
                <div class="card-body">
                    <h5 class="card-title text-center">Sign In</h5>
                    <form class="form-signin" method="post">
                        <input type="hidden" name="login" value="signin"/>
                        <div class="form-label-group">
                            <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                            <label for="inputEmail">Email address</label>
                        </div>

                        <div class="form-label-group">
                            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
                            <label for="inputPassword">Password</label>
                        </div>

                        <?php
                            if(isset($error)) {
                                echo "<p class='text-danger'>$error</p>";
                            }
                        ?>

                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" name="remember" class="custom-control-input" id="rememberCheck">
                            <label class="custom-control-label" for="rememberCheck">Remember password</label>
                        </div>
                        
                        <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Sign in</button>
                        
                        <hr class="my-4">
                        <button class="btn btn-lg btn-google btn-block text-uppercase" type="submit"><i class="fa fa-google mr-2"></i> Sign in with Google</button>
                        <button class="btn btn-lg btn-facebook btn-block text-uppercase" type="submit"><i class="fa fa-facebook-f mr-2"></i> Sign in with Facebook</button>
                        
                        <hr class="my-4">
                        <a href="javascript:{}" onclick="$('input[name=login]').val('register'); $('form').submit();">Not already a member ? Register !</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>