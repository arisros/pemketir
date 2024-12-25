<?php
$title = 'Form Data Uji';
ob_start();

require_once 'utils/button_back.php';
require_once "libs/koneksi.php";
require_once "libs/fungsi.php";

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $data_bersih = mysqli_real_escape_string($conn, $_POST['data_bersih']);
  $id_actual = mysqli_real_escape_string($conn, $_POST['id_actual']);

  // Menyimpan data uji ke dalam tabel 'classify'
  $sql = "INSERT INTO classify (data_bersih, id_actual) VALUES ('$data_bersih', '$id_actual')";
  if (mysqli_query($conn, $sql)) {
    echo "<div class='alert alert-success' role='alert'>Data uji berhasil disimpan!</div>";
  } else {
    echo "<div class='alert alert-danger' role='alert'>Gagal menyimpan data uji: " . mysqli_error($conn) . "</div>";
  }
}

// Ambil semua kategori untuk dropdown prediksi
$sql_kategori = "SELECT * FROM kategori";
$result_kategori = mysqli_query($conn, $sql_kategori);
?>

<div class="container mt-5">
  <h2>Form Input Data Uji dan Klasifikasi</h2>
  <form method="POST" action="" class="mt-4">
    <div class="mb-3">
      <label for="data_bersih" class="form-label">Data Bersih</label>
      <textarea name="data_bersih" id="data_bersih" class="form-control" rows="5" required></textarea>
    </div>

    <div class="mb-3">
      <label for="id_actual" class="form-label">Prediksi Kategori</label>
      <select name="id_actual" id="id_actual" class="form-select" required>
        <option value="">Pilih Kategori</option>
        <?php while ($kategori = mysqli_fetch_array($result_kategori)) { ?>
          <option value="<?php echo $kategori['id_kategori']; ?>"><?php echo $kategori['nm_kategori']; ?></option>
        <?php } ?>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
  </form>

  <h2 class="mt-5">Data Uji dan Klasifikasi</h2>

  <table class="table table-bordered table-striped table-hover mt-4">
    <thead class="table-warning">
      <tr>
        <th style="width: 5%;">No</th>
        <th>Data Bersih</th>
        <th style="width: 15%;">Prediksi Kategori</th>
        <th style="width: 15%;">Aktual Kategori</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Menampilkan data uji dan klasifikasi
      $sql_classify = "SELECT * FROM classify";
      $result_classify = mysqli_query($conn, $sql_classify);
      $no = 1;
      while ($d = mysqli_fetch_array($result_classify)) {
      ?>
        <tr>
          <td><?php echo $no; ?></td>
          <td><?php echo htmlspecialchars($d['data_bersih']); ?></td>
          <td><?php echo getKat($conn, $d['id_predicted']); ?></td>
          <td><?php echo $d['id_actual'] ? getKat($conn, $d['id_actual']) : '-'; ?></td>
        </tr>
      <?php
        $no++;
      }
      ?>
    </tbody>
  </table>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>