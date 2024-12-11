<?php
include("env.php");

$host = getenv("DB_HOST");
$user = getenv("DB_USER");
$pass = getenv("DB_PASS");
$port = getenv("DB_PORT");
$dbName = getenv("DB_NAME");
$dbSocket = getenv("DB_SOCKET");
$conn = mysqli_connect($host, $user, null, $dbName, intval($port), $dbSocket);

if (!$conn) {
  die("failed connect mysql" . mysqli_connect_error());
}

$sql = mysqli_select_db($conn, $dbName);
if (!$sql) {
  die("failed connect db " . mysqli_connect_error());
}
