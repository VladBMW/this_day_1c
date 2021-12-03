<?php
session_start();

$config = include("config.php");
require_once $config->root . "/modules/database.php";

$db = new Database();
$db->connectToDB($config->db_name, $config->host, $config->db_user, $config->db_password);
