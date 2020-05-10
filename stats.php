<?php
include("includes/a_config.php");
include("includes/db/index.php");
include("includes/db/theme.php");
include("includes/db/picture.php");
include("includes/db/user.php");

if (!$USER_LOGGED || !$USER_IS_MODERATOR) {
    header('Location: index.php');
    exit();
}

$pdo = getConnection();

$userCount = getUserCount($pdo);
$picCount = getPicturesCount($pdo);
$picAverage = $userCount == 0 ? 0 : $picCount / $userCount;

closeConnexion($pdo);

?>
<!DOCTYPE html>
<html>

<head>
    <?php include("includes/head-tag-contents.php"); ?>
    <link rel="stylesheet" href="styles/stats.css">
</head>

<body class="bg-light">
    <?php include("includes/navigation.php"); ?>

    <div class="card container bg-white shadow p-3 mb-5" id="main-content">
        <h2 class="mb-4">Statistiques du site</h2>
        <h5> Il y a: <span style="color:red"><?= $userCount ?></span> inscrits sur Pic !</h5>
        <h5>Au total, <span style="color:#4e96b8"><?= $picCount ?></span> photos ont été postées sur Pic !</h5>
        <h5>Sur Pic, chaque utilisateur poste en moyenne <span style="color:gray"><?= number_format($picAverage, 2, ".", ",") ?></span> photo(s) !</h5>
    </div>

    <?php include("includes/footer.php"); ?>

</body>

</html>