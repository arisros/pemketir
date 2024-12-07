<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tahap Labelisasi</title>
  <?php include 'bootstrap.php'; ?>
</head>

<body>

  <?php include 'utils/button_back.php'; ?>

  <?php
  include 'koneksi.php';
  include 'stopword.php';

  $sql = "SELECT * FROM galert_data where length(galert_id)!=0";
  $result = $conn->query($sql);

  if ($result->num_rows == 0) {
    echo "Data tidak ditemukan";
  } else {
  ?>
    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
          <td colspan="" 3>
            <strong>Daftar Kategori</strong>
          </td>
        </tr>
        <tr background="#ccc">
          <th>
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
          <td><?php echo $i; ?></td>
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

      if ($result2) {
        echo "Data berhasil di labelisasi";
      } else {
        echo "Data gagal di labelisasi";
      }
    }

    ?>
    <br><br>
    <?php

    $i = 1;
    $sql = "SELECT * FROM preprocessing where length(entry_id)!=0";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
      echo "Data tidak ditemukan";
    } else {
    ?>
      <table class="" table table-bordered table-striped table-hover>
        <thead>
          <tr>
            <td colspan="3">
              <strong>Daftar Labelisasi Data</strong>
            </td>
          </tr>
          <tr background="#ccc">
            <th>No.</th>
            <th>Data Bersih</th>
            <th>Nama Kategori</th>
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
            <td><?php echo $i; ?></td>
            <td><?php echo $data_bersih; ?></td>
            <td><?php echo $nm_kategori; ?></td>
          </tr>
        <?php
          $i = $i + 1;
        }
        ?>
      </table>
    <?php
    }

    ?>

</body>

</html>