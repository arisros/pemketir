<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tahap Klasifikasi Data Dengan ALgoritma Naive Bayes Classifier</title>
  <?php include 'bootstrap.php'; ?>
</head>

<body>

  <?php include 'utils/button_back.php'; ?>


  <br>

  <?php

  include "libs/koneksi.php";
  include "libs/fungsi.php";

  $sql = "SELECT id_kategori FROM kategori";
  $result = $conn->query($sql);
  if ($result->num_rows == 0) {
    echo "Data Tidak Ditemukan";
  } else {
  ?>
    <table class="table table-bordered table-striped table-hover">
      <thead>
        <thead>

          <tr>
            <td colspan="5">
              <strong>
                Menghitung Probabilities Setiap Kategori
              </strong>
            </td>
          </tr>
          <tr bgcolor="#CCC">
            <th>
              No.
            </th>
            <th>
              Kategori
            </th>
            <th>
              Frekuensi Dokumen Pada Setiap Kategori
            </th>
            <th>
              Jumlah Dokumen Yang Ada
            </th>
            <th>
              Nilai Probabilities
            </th>
          </tr>
        </thead>
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
  ?>
</body>

</html>