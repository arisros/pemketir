<?php
$title = 'Labelisasi Data';
ob_start();
?>

<?php include 'utils/button_back.php'; ?>

<?php
include 'libs/koneksi.php';
include 'libs/stopword.php';

$sql = "SELECT * FROM galert_data where length(galert_id)!=0";
$result = $conn->query($sql);

if ($result->num_rows == 0 || $result == false) {
  echo "Data tidak ditemukan";
} else {
?>
  <h2>Daftar Kategori</h2>
  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr background="#ccc">
        <th class="table-id-column">
          No.
        </th>
        <th>
          Title
        </th>
        <th>Nama Kategori</th>

      </tr>
    </thead>

    <?php
    $i = 1;
    while ($d = mysqli_fetch_array($result)) {
      $id = $d['galert_id'];
      $title = $d['galert_title'];

      $stoplist = array('Google', 'Alert', ' ', '-');
      $rem_stopword = explode(' ', $title);
      $str_data = array();

      foreach ($rem_stopword as $value) {
        if (!in_array($value, $stoplist)) {
          $str_data[] = "" . $value;
        }
      }
      $kategori = implode(" ", $str_data);

    ?>

      <tr background="#FFF">
        <td class="table-id-column"><?php echo $i; ?></td>
        <td><?php echo $title; ?></td>
        <td><?php echo $kategori; ?></td>
      </tr>

    <?php

      $sql = "SELECT * FROM kategori where nm_kategori='$kategori'";
      $result1 = $conn->query($sql);
      if ($result1->num_rows == 0) {
        $q = "INSERT INTO kategori (nm_kategori) VALUES ('$kategori')";
        $result = $conn->query($q);
      }

      $sql = "SELECT id_kategori FROM kategori where nm_kategori='$kategori'";
      $result2 = $conn->query($sql);

      $d = mysqli_fetch_row($result2);
      $id_kategori = $d[0];

      $sql = "SELECT * FROM galert_entry where feed_id='$id'";
      $result2 = $conn->query($sql);

      if ($result2->num_rows > 0) {
        $q = "UPDATE preprocessing set id_kategori='$id_kategori' where entry_id in(SELECT entry_id FROM galert_entry where feed_id='$id')";
        $result2 = $conn->query($q);
      }

      $i = $i + 1;
    }
    ?>
  </table>
  <?php

  if ($result2) {

  ?>
    <div class="alert alert-success" role="alert">
      Data berhasil di labelisasi
    </div>
  <?php
  } else {
    echo "Data gagal di labelisasi";
  ?>
    <div class="alert alert-danger" role="alert">
      Data gagal di labelisasi
    </div>
<?php
  }
}

?>

<?php

$i = 1;
$sql = "SELECT * FROM preprocessing where length(entry_id)!=0";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
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
    </thead>
    <?php
    while ($d = mysqli_fetch_array($result)) {
      $data_bersih = $d["data_bersih"];
      $id_kategori = $d["id_kategori"];
      $sql = "SELECT nm_kategori FROM kategori where id_kategori='$id_kategori'";
      $result2 = $conn->query($sql);
      $d = mysqli_fetch_row($result2);
      $nm_kategori = $d[0];

    ?>
      <tr background="#FFF">
        <td class="table-id-column"><?php echo $i; ?></td>
        <td><?php echo $data_bersih; ?></td>
        <td style="width: 12%;"><?php echo $nm_kategori; ?></td>
      </tr>
    <?php
      $i = $i + 1;
    }
    ?>
  </table>
<?php
}


$content = ob_get_clean();
include 'layout.php';
?>