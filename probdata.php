<?php
require_once "libs/koneksi.php";
require_once "libs/fungsi.php";

$sql = "delete FROM P_Data";
$result = mysqli_query($conn, $sql);

$sql = "SELECT * FROM preprocessing where id_kategori is not null";
$result1 = mysqli_query($conn, $sql);

if ($result1->num_rows == 0) {
  echo "Data tidak ditemukan";
} else {
  while ($d = mysqli_fetch_array($result1)) {
    $data = $d['data_bersih'];
    $data_array = explode(' ', $data);
    $str_data = array();

    foreach ($data_array as $value) {

      $str_data[] = "" . $value;
      $kata = $value;

      $sql = "SELECT * FROM kategori";
      $result2 = mysqli_query($conn, $sql);

      if ($result2->num_rows == 0) {
        echo "Data tidak ditemukan";
      } else {
        while ($d = mysqli_fetch_array($result2)) {
          $id = $d[0];
          $nm = $d[1];

          $jumKata = getJmlKata($conn, $kata, $id);
          $sql = "SELECT * FROM P_Data where kata = '$kata' and id_kategori = '$id'";
          $result3 = mysqli_query($conn, $sql);

          if ($result3->num_rows == 0) {
            $q = "INSERT INTO P_Data (kata, id_kategori, jml_data) VALUES ('$kata', '$id', '$jumKata')";
            $result3 = mysqli_query($conn, $q);
          } else {
            $q = "UPDATE P_Data set jml_data = '$jumKata' where kata = '$kata' and id_kategori = '$id'";
            $result3 = mysqli_query($conn, $q);
          }
        }
      }
    }
  }
}


$sql = "SELECT * FROM P_Data order by kata";
$result4 = mysqli_query($conn, $sql);
?>

<h2>Probabilitas Kriteria Data Bersih pada Kategori</h2>
<table class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Kategori</th>
      <th>Kata</th>
      <th>Jumlah Kata Per Kategori</th>
      <th>Jumlah Data Per Kategori</th>
      <th>Probabilias (Jumlah Data Per Data/Jumlah Data)</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $no = 1;
    while ($d = mysqli_fetch_array($result4)) {
    ?>
      <tr>
        <td><?php echo $no; ?></td>
        <td><?php echo getKat($conn, $d[1]) ?></td>
        <td><?php echo $d[0]; ?></td>
        <td><?php echo $d[2]; ?></td>
        <td><?php echo getJmlKat($conn, $d[1]); ?></td>
        <?php


        $N = getN($conn, $d[1]);
        $kosakata = getKosakata($conn);
        $nilai = ($d[2] + 1) / ($N + $kosakata);
        $sql = "SELECT * FROM P_Data where kata = '$d[0]' and id_kategori = '$d[1]'";

        $result5 = mysqli_query($conn, $sql);
        if ($result5->num_rows > 0) {
          $q = "UPDATE P_Data set nilai = '$nilai' where kata = '$d[0]' and id_kategori = '$d[1]'";
          $result5 = mysqli_query($conn, $q);
        }

        ?>
        <td><?php echo $nilai; ?></td>
      </tr>
    <?php
      $no++;
    }
    ?>
  </tbody>
</table>