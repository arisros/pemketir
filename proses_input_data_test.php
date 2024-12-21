<?php
// this will handle form input data test

include 'libs/koneksi.php';
include 'libs/fungsi.php';
include 'utils/button_back.php';

if (isset($_POST['data'])) {
  $data = $_POST['data'];
  $kategori = $_POST['kategori'];

  $sql = "INSERT INTO data_test (data_test, id_kategori) VALUES ('$data', '$kategori')";
  $conn->query($sql);
  echo "Data Berhasil Disimpan";
}
