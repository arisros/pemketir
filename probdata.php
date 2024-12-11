<?php
require_once "libs/koneksi.php";
require_once "libs/fungsi.php";

$sql = "SELECT * FROM preprocessing where id_kategori is no null";
$result1 = mysqli_query($con, $sql);

if ($result1->num_rows == 0) {
  echo "Data tidak ditemukan";
} else {
  while ($d = mysqli_fetch_array($result1)) {
    while ($d = mysqli_fetch_array($result1)) {
      $data = $d['data_bersih'];
      $data_array = explode(' ', $data);

      $str_data = array();

      foreach ($data_array as $value) {

        $str_data[] = "" . $word;
        $kata = $value;

        $sql = "SELECT * FROM kategori";
        $result2 = mysqli_query($con, $sql);

        if ($result2->num_rows == 0) {
          echo "Data tidak ditemukan";
        } else {
          while ($d = mysqli_fetch_array($result2)) {
            $id = $d[0];
            $nm = $d[1];

            $jumKata = getJmlKat($conn, $kata, $id); //fungsi.php there is no id parameter

            $sql = "SELECT * FROM P_Data where kata = '$kata' and id_kategori = '$id'";
            $result3 = mysqli_query($con, $sql);

            if ($result3->num_rows == 0) {
              $q = "INSERT INTO P_Data (kata, id_kategori, jum_kata) VALUES ('$kata', '$id', '$jumKata')";
              $result3 = mysqli_query($con, $q);
            } else {
              $q = "UPDATE P_Data set jum_kata = '$jumKata' where kata = '$kata' and id_kategori = '$id'";
              $result3 = mysqli_query($con, $q);
            }
          }
        }
      }
    }
  }
}

$sql = "SELECT * FROM P_Data order by kata";
$result4 = mysqli_query($con, $sql);
?>

<h2>Probabilitas Kireteria Data Bersih pada Kategori</h2>
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
        // dont know where is function
        // TODO: get N and get kosakata
        // $N = getJmlKat($conn, $d[1]);
        // $kosakata = getKosakata($conn);

        ?>
      </tr>
    <?php
      $no++;
    }
    ?>
  </tbody>
</table>