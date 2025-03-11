<?php

session_start();

$user_id = $_SESSION["user_id"];

session_unset();
session_destroy();
setcookie("user", $user_id, time() - (86400 * 30), "/");

header("Location: ../../frontend/index.php");
exit();