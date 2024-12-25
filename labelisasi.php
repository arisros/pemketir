<?php
$title = 'Labelisasi Data';
ob_start();
?>

<?php include 'utils/button_back.php'; ?>

<?php
include 'libs/koneksi.php';
include 'libs/stopword.php';

$sql = "SELECT * FROM galert_data WHERE LENGTH(galert_id) != 0";
$result = $conn->query($sql);

if (!$result) {
  echo "Query error: " . $conn->error;
} elseif ($result->num_rows == 0) {
  echo "Data tidak ditemukan";
} else {
?>
  <h2>Daftar Kategori</h2>
  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr background="#ccc">
        <th class="table-id-column">No.</th>
        <th>Title</th>
        <th>Nama Kategori</th>
      </tr>
    </thead>

    <?php
    $i = 1;
    while ($data = mysqli_fetch_array($result)) {
      $id = $data['galert_id'];
      $title = $data['galert_title'];

      $stoplist = ['Google', 'Alert', ' ', '-'];
      $rem_stopword = explode(' ', $title);
      $filtered_data = array_filter($rem_stopword, function ($word) use ($stoplist) {
        return !in_array($word, $stoplist);
      });
      $kategori = implode(" ", $filtered_data);
    ?>

      <tr background="#FFF">
        <td class="table-id-column"><?php echo $i; ?></td>
        <td><?php echo $title; ?></td>
        <td><?php echo $kategori; ?></td>
      </tr>

    <?php
      $sqlKategori = "SELECT * FROM kategori WHERE nm_kategori='$kategori'";
      $resultKategori = $conn->query($sqlKategori);

      if (!$resultKategori || $resultKategori->num_rows == 0) {
        $insertKategori = "INSERT INTO kategori (nm_kategori) VALUES ('$kategori')";
        $conn->query($insertKategori);
      }

      $sqlKategoriId = "SELECT id_kategori FROM kategori WHERE nm_kategori='$kategori'";
      $resultKategoriId = $conn->query($sqlKategoriId);

      if ($resultKategoriId && $kategoriRow = mysqli_fetch_row($resultKategoriId)) {
        $id_kategori = $kategoriRow[0];
        $sqlEntry = "SELECT * FROM galert_entry WHERE feed_id='$id'";
        $resultEntry = $conn->query($sqlEntry);

        if ($resultEntry && $resultEntry->num_rows > 0) {
          $updatePreprocessing = "UPDATE preprocessing SET id_kategori='$id_kategori' WHERE entry_id IN (SELECT entry_id FROM galert_entry WHERE feed_id='$id')";
          $conn->query($updatePreprocessing);
        }
      }
      $i++;
    }
    ?>
  </table>
  <?php
  if ($conn->affected_rows > 0) {
  ?>
    <div class="alert alert-success" role="alert">
      Data berhasil di labelisasi
    </div>
  <?php
  } else {
  ?>
    <div class="alert alert-danger" role="alert">
      Data gagal di labelisasi
    </div>
  <?php
  }
}

$sqlPreprocessing = "SELECT * FROM preprocessing WHERE LENGTH(entry_id) != 0";
$resultPreprocessing = $conn->query($sqlPreprocessing);

if (!$resultPreprocessing) {
  echo "Query error: " . $conn->error;
} elseif ($resultPreprocessing->num_rows == 0) {
  echo "Data tidak ditemukan";
} else {
  ?>
  <h2>Daftar Labelisasi Data</h2>
  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr background="#ccc">
        <th class="table-id-column">No.</th>
        <th>Data Bersih</th>
        <th style="width: 12%;">Nama Kategori</th>
      </tr>
    </thead>
    <?php
    $i = 1;
    while ($dataPreprocessing = mysqli_fetch_array($resultPreprocessing)) {
      $data_bersih = $dataPreprocessing["data_bersih"];
      $id_kategori = $dataPreprocessing["id_kategori"];

      $sqlKategori = "SELECT nm_kategori FROM kategori WHERE id_kategori='$id_kategori'";
      $resultKategori = $conn->query($sqlKategori);

      if ($resultKategori && $kategoriRow = mysqli_fetch_row($resultKategori)) {
        $nm_kategori = $kategoriRow[0];
    ?>
        <tr background="#FFF">
          <td class="table-id-column"><?php echo $i; ?></td>
          <td><?php echo $data_bersih; ?></td>
          <td style="width: 12%;"><?php echo $nm_kategori; ?></td>
        </tr>
    <?php
        $i++;
      }
    }
    ?>
  </table>
<?php
}

$content = ob_get_clean();
include 'layout.php';
?>