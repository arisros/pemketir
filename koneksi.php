<?php
// ini juga kudu diliat ente naro dimana database server ente ada dirumah siapa?
$host = "localhost";
// sesuain sama username db ente
$user = "root";
// sesuain sama password db ente
$pass = "";
// kudunya masih sama kalo ikutin doc
$dbName = "db_pemketir";
$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
  die("failed connect mysql" . mysqli_connect_error());
}

echo "success connect db! <br>";

$sql = mysqli_select_db($conn, $dbName);
if (!$sql) {
  die("failed connect db " . mysqli_connect_error());
}

echo "success connect db";
