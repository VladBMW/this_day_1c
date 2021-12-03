<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root . "/db.php";

unset($_SESSION["logged_user"]);
header("Location: index.php");
