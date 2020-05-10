<?php

include_once("includes/a_config.php");
unset($_SESSION["user"]);
header('Location: index.php');
exit();

?>