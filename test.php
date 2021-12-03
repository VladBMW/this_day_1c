<?php

require_once("db.php");


if (isset($_GET["day"])) {
  $res .= $_GET["day"];
} else {
  echo "Error";
  exit;
}

if (isset($_GET["month"])) {
  $res .= " - " . $_GET["month"];
} else {
  echo "Error";
  exit;
}


$res = $db->searchEventOfThisDay($config->db_name, $config->host, $config->db_user, $config->db_password, $_GET["day"], $_GET["month"]);

$events = [];

foreach ($res as $row) {
  array_push($events, $row);
}

$json = json_encode($events, JSON_UNESCAPED_UNICODE);
echo $json;
?>
