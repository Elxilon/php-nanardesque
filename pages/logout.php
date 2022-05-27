<?php
require __DIR__ . "/../config.php";

session_start($GLOBALS['session_options']);
session_destroy();
header("Location: ". __RPRJ__ . "index.php");
exit();