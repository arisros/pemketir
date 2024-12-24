<?php
$title = 'Klasifikasi';
ob_start();


include 'utils/button_back.php';
include "libs/koneksi.php";
include "libs/fungsi.php";

$sql = "SELECT id_kategori FROM kategori";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
  echo "Data Tidak Ditemukan";
} else {
?>
  <h2>Menghitung Probabilities Setiap Kategori</h2>
  <table class="table table-bordered table-striped table-hover">
    <thead>

      <tr bgcolor="#CCC">
        <th style="width: 10%;">
          No.
        </th>
        <th style="width: 10%;">
          Kategori
        </th>
        <th>
          Frekuensi Dokumen Pada Setiap Kategori
        </th>
        <th style="width: 20%;">
          Jumlah Dokumen Yang Ada
        </th>
        <th style="width: 20%;">
          Nilai Probabilities
        </th>

      </tr>
    </thead>

    <?php
    $i = 1;
    $totK = 0;
    while ($d = mysqli_fetch_array($result)) {
      $id = $d['id_kategori'];
      $jumlK = getJmlKat($conn, $id);
      $jumlA = getJmlAll($conn); // typo id

      $nmKategori = getKat($conn, $id);
      $nilai = $jumlK / $jumlA;

      $sql = "SELECT * FROM P_Kategori where id_kategori='$id'";
      $result2 = $conn->query($sql);

      if ($result2->num_rows == 0) {
        $q = "INSERT INTO P_Kategori(id_kategori, jml_data,nilai) VALUES('$id', '$jumlK', '$nilai')";

        $result2 = mysqli_query($conn, $q);
      } else {
        $q = "UPDATE P_Kategori SET jml_data='$jumlK', nilai='$nilai' where id_kategori='$id'";

        $result2 = mysqli_query($conn, $q);
      }
    ?>
      <tr bgcolor="#FFFF">
        <td>
          <?php echo $i; ?>
        </td>
        <td>
          <?php echo $nmKategori; ?>
        </td>
        <td>
          <?php echo $jumlK; ?>
        <td>
          <?php echo $jumlA; ?>
        </td>
        <td>
          <?php echo $nilai; ?>
        </td>
      </tr>
    <?php
      $i++;
    }
    ?>
  </table>
<?php
}
$content = ob_get_clean();
include 'layout.php';
?>