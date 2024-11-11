<?php
include("libs/env.php");

$host = getenv("DB_HOST");
$user = getenv("DB_USER");
$pass = getenv("DB_PASS");
$dbName = getenv("DB_NAME");
$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
  die("failed connect mysql" . mysqli_connect_error());
}

$sql = mysqli_select_db($conn, $dbName);
if (!$sql) {
  die("failed connect db " . mysqli_connect_error());
}
