<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=, initial-scale=1.0">
  <title>Input Data Test</title>
</head>

<body>

  <?php
  include 'libs/koneksi.php';
  include 'libs/fungsi.php';
  include 'utils/button_back.php';


  ?>

  <!-- create form input data test -->
  <form action="proses_input_data_test.php" method="POST">
    <table>
      <tr>
        <td>
          <label for="data">Data Test</label>
        </td>
        <td>
          <textarea name="data" id="data" cols="30" rows="10"></textarea>
        </td>
      </tr>
      <tr>
        <td>
          <label for="kategori">Kategori</label>
        </td>
        <td>
          <select name="kategori" id="kategori">
            <?php
            $sql = "SELECT * FROM kategori";
            $result = $conn->query($sql);
            while ($d = mysqli_fetch_array($result)) {
            ?>
              <option value="<?php echo $d['id_kategori']; ?>"><?php echo $d['nm_kategori']; ?></option>
            <?php
            }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>
          <button type="submit">Simpan</button>
        </td>
      </tr>
    </table>
</body>

</html>