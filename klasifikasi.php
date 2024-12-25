<?php
$title = 'Klasifikasi';
ob_start();

include 'utils/button_back.php';
include "libs/koneksi.php";
include "libs/fungsi.php";

// Mendapatkan data kategori
$sql = "SELECT id_kategori FROM kategori";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
  echo "Data Tidak Ditemukan";
} else {
?>
  <h2>Menghitung Probabilitas Setiap Kategori dan Sentimen</h2>
  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr bgcolor="#CCC">
        <th style="width: 5%;">No.</th>
        <th style="width: 15%;">Kategori</th>
        <th style="width: 10%;">Frekuensi Dokumen</th>
        <th style="width: 10%;">Jumlah Dokumen</th>
        <th style="width: 15%;">Probabilitas Kategori</th>
        <th style="width: 15%;">Probabilitas Sentimen</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $i = 1;

      // Mendapatkan semua kategori dan menghitung probabilitas kategori
      while ($d = mysqli_fetch_array($result)) {
        $id = $d['id_kategori'];
        $jumlK = getJmlKat($conn, $id); // jumlah dokumen pada kategori
        $jumlA = getJmlAll($conn); // jumlah seluruh dokumen

        $nmKategori = getKat($conn, $id);
        $nilaiKategori = $jumlK / $jumlA;

        // Mengambil data dan sentimen yang sudah dilabeli
        $sqlData = "SELECT sentiment_label FROM preprocessing WHERE id_kategori = '$id'";
        $dataResult = $conn->query($sqlData);

        $countPositive = 0;
        $countNegative = 0;
        $countNeutral = 0;

        while ($dataRow = mysqli_fetch_array($dataResult)) {
          $sentiment = $dataRow['sentiment_label'];

          // Menghitung frekuensi masing-masing sentimen
          if ($sentiment == 'positive') $countPositive++;
          elseif ($sentiment == 'negative') $countNegative++;
          else $countNeutral++;
        }

        // Probabilitas sentimen
        $totalSentimen = $countPositive + $countNegative + $countNeutral;
        $probPositive = $totalSentimen > 0 ? $countPositive / $totalSentimen : 0;
        $probNegative = $totalSentimen > 0 ? $countNegative / $totalSentimen : 0;
        $probNeutral = $totalSentimen > 0 ? $countNeutral / $totalSentimen : 0;

        // Simpan data kategori ke P_Kategori jika belum ada
        $sql = "SELECT * FROM P_Kategori WHERE id_kategori='$id'";
        $result2 = $conn->query($sql);

        if ($result2->num_rows == 0) {
          // Jika data kategori belum ada, insert data baru
          $q = "INSERT INTO P_Kategori (id_kategori, jml_data, nilai, prob_positive, prob_negative, prob_neutral) 
              VALUES ('$id', '$jumlK', '$nilaiKategori', '$probPositive', '$probNegative', '$probNeutral')";
          $conn->query($q);
        } else {
          // Jika data kategori sudah ada, update data yang sudah ada
          $q = "UPDATE P_Kategori 
              SET jml_data='$jumlK', nilai='$nilaiKategori', 
                  prob_positive='$probPositive', prob_negative='$probNegative', prob_neutral='$probNeutral' 
              WHERE id_kategori='$id'";
          $conn->query($q);
        }
      ?>
        <tr>
          <td><?php echo $i; ?></td>
          <td><?php echo $nmKategori; ?></td>
          <td><?php echo $jumlK; ?></td>
          <td><?php echo $jumlA; ?></td>
          <td><?php echo $nilaiKategori; ?></td>
          <td>
            <b>Positive:</b> <?php echo $probPositive; ?> <br>
            <b>Negative:</b> <?php echo $probNegative; ?> <br>
            <b>Neutral:</b> <?php echo $probNeutral; ?> <br>
          </td>
        </tr>
      <?php
        $i++;
      }
      ?>
    </tbody>
  </table>
<?php
}

include 'probdata.php';

$content = ob_get_clean();
include 'layout.php';
?>