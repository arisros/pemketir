<?php
$title = 'Form Data Uji';
ob_start();

require_once 'utils/button_back.php';
require_once "libs/koneksi.php";
require_once "libs/fungsi.php";

// Proses Klasifikasi Data Uji berdasarkan Tabel 'classify'
$sql1 = "SELECT * FROM classify WHERE id_actual IS NULL"; // Ambil data yang belum diprediksi
$result1 = mysqli_query($conn, $sql1);

if ($result1->num_rows > 0) {
  while ($d = mysqli_fetch_array($result1)) {
    $data_bersih = $d['data_bersih'];
    $data_array = explode(' ', $data_bersih); // Pisahkan kata

    // Inisialisasi array untuk menyimpan probabilitas kategori
    $kategori_probs = [];

    // Ambil kategori dari tabel kategori
    $sql_kategori = "SELECT * FROM kategori";
    $result_kategori = mysqli_query($conn, $sql_kategori);

    if (mysqli_num_rows($result_kategori) > 0) {
      while ($kategori = mysqli_fetch_array($result_kategori)) {
        $id_kategori = $kategori['id_kategori'];

        // Inisialisasi probabilitas kategori
        $prob_kategori = getNilaiKategori($conn, $id_kategori);
        $prob_kategori_terakhir = $prob_kategori; // Simpan probabilitas kategori

        // Loop setiap kata dalam data uji dan hitung probabilitasnya
        foreach ($data_array as $kata) {
          $sql_probabilitas = "SELECT * FROM P_Data WHERE kata = '$kata' AND id_kategori = '$id_kategori'";
          $result_probabilitas = mysqli_query($conn, $sql_probabilitas);

          if (mysqli_num_rows($result_probabilitas) > 0) {
            $probabilitas_kata = mysqli_fetch_array($result_probabilitas)['nilai'];
            $prob_kategori_terakhir *= $probabilitas_kata;
          } else {
            // Kata tidak ditemukan di P_Data, anggap probabilitasnya 1
            $prob_kategori_terakhir *= 1;
          }
        }

        // Simpan probabilitas kategori untuk perbandingan
        $kategori_probs[$id_kategori] = $prob_kategori_terakhir;
      }

      // Tentukan kategori dengan probabilitas tertinggi
      $predicted_kategori = array_keys($kategori_probs, max($kategori_probs))[0];

      // Update hasil klasifikasi pada tabel classify
      $sql_update = "UPDATE classify SET id_ac = '$predicted_kategori' WHERE data_bersih = '$data_bersih'";
      mysqli_query($conn, $sql_update);
    }
  }
}

$sql = "SELECT classify.data_bersih, classify.id_actual, classify.id_predicted, 
                actual_kategori.nm_kategori AS actual_kategori_name, 
                predicted_kategori.nm_kategori AS predicted_kategori_name
        FROM classify
        JOIN kategori AS actual_kategori ON classify.id_actual = actual_kategori.id_kategori
        JOIN kategori AS predicted_kategori ON classify.id_predicted = predicted_kategori.id_kategori";

$result = mysqli_query($conn, $sql);
?>

<h2>Hasil Klasifikasi Data Uji</h2>
<table class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th style="width: 5%;">No</th>
      <th>Data Bersih</th>
      <th style="width: 15%;">Kategori Aktual</th>
      <th style="width: 15%;">Kategori Prediksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $no = 1;
    while ($d = mysqli_fetch_array($result)) {
    ?>
      <tr>
        <td><?php echo $no; ?></td>
        <td><?php echo htmlspecialchars($d['data_bersih']); ?></td>
        <td><?php echo htmlspecialchars($d['actual_kategori_name']); ?></td>
        <td><?php echo htmlspecialchars($d['predicted_kategori_name']); ?></td>
      </tr>
    <?php
      $no++;
    }
    ?>
  </tbody>
</table>


<?php
$content = ob_get_clean();
include 'layout.php';
?>