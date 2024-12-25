<?php

$title = 'Preprocessing';
ob_start();
?>
<?php include 'utils/button_back.php'; ?>

<?php
include 'libs/koneksi.php';
include 'libs/stopword.php';

require_once __DIR__ . '/vendor/autoload.php';

$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer = $stemmerFactory->createStemmer();

echo "Preprocessing Data...<br>";

$sql = "SELECT k_baku,concat(k_baku, ' ') k_baku from slangword";
$stmt = $conn->prepare($sql);
$stmt->execute();
$resultSet = $stmt->get_result();
$result = $resultSet->fetch_all();

$arr_slang = array();

foreach ($result as $k => $v) {
  $arr_slang[$v[0]] = $v[1];
}

$sql = "SELECT * FROM galert_entry where length(entry_id)!=0";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
  echo "Data tidak ditemukan";
} else {
?>
  <table class="table table-bordered table-striped">
    <thead class="table-warning">
      <tr>
        <th>ID</th>
        <th>Content</th>
        <th>Case Folding</th>
        <th>Hapus Simbol</th>
        <th>Filter Slang Word</th>
        <th>Filter Stop Word</th>
        <th>Stemming</th>
        <th>Tokenisasi</th>
      </tr>
    </thead>

    <?php
    while ($d = mysqli_fetch_array($result)) {
      $id = $d['entry_id'];
      $content = $d['entry_content'];

      // 1. Case Folding
      $cf = strtolower($content); // Case folding
      // Mengganti &nbsp; dengan spasi biasa
      $cf = str_replace('&nbsp;', ' ', $cf);
      $cf = str_replace('&amp;', ' ', $cf);
      $cf = str_replace('&quot;', ' ', $cf);
      $cf = str_replace('&lt;', ' ', $cf);
      $cf = str_replace('&gt;', ' ', $cf);
      $cf = str_replace('<b>', ' ', $cf);
      $cf = str_replace('</b>', ' ', $cf);

      // 2. Penghapusan Simbol-Simbol (Symbol Removal)
      $simbol = preg_replace('/[^a-zA-Z\s]/', '', $cf);

      // 3. Konversi Slangword
      $slangword = str_replace(array_keys($arr_slang), $arr_slang, $simbol);

      // 4. Stopword Removal
      $rem_slang = explode(' ', $slangword); // Slangwords sudah menjadi array
      $str_data = array();
      foreach ($rem_slang as $word) {
        if (!in_array($word, $stopwords)) {
          $str_data[] = $word;
        }
      }

      $stopword = implode(' ', $str_data);

      // 5. Stemming
      $q1 = implode(' ', (array)$str_data);
      $stemming = $stemmer->stem($q1);

      // 6. Tokenisasi
      $tokenisasi = preg_split('/\s+/', $stemming);
      $tokenisasi = implode(' ', $tokenisasi);

      // Membersihkan teks dari tag HTML atau entitas
      $cf = strip_tags($cf);
      $simbol = strip_tags($simbol);
      $slangword = strip_tags($slangword);
      $stopword = strip_tags($stopword);
      $stemming = strip_tags($stemming);
      $tokenisasi = strip_tags($tokenisasi);
      $tokenisasi = str_replace('&nbsp;', ' ', $tokenisasi);

    ?>
      <tr>
        <td class="table-id-column"><?php echo $id; ?></td>
        <td><?php echo $content; ?></td>
        <td><?php echo $cf; ?></td>
        <td><?php echo $simbol; ?></td>
        <td><?php echo $slangword; ?></td>
        <td><?php echo $stopword; ?></td>
        <td><?php echo $stemming; ?></td>
        <td><?php echo $tokenisasi; ?></td>

      </tr>
  <?php

      $sql = "SELECT * FROM preprocessing where entry_id='$id'";
      $result1 = $conn->query($sql);

      if ($result1->num_rows == 0) {
        $q = "INSERT INTO preprocessing (entry_id, p_cf, p_simbol, p_tokenisasi, p_sword, p_stopword, p_stemming, data_bersih) VALUES ('$id', '$cf', '$simbol', '$tokenisasi', '$slangword', '$stopword', '$stemming', '$stemming')";

        $result1 = $conn->query($q);
      } else {
        $q = "UPDATE preprocessing set p_cf='$cf', p_simbol='$simbol', p_tokenisasi='$tokenisasi', p_sword='$slangword', p_stopword='$stopword', p_stemming='$stemming', data_bersih='$stemming' where entry_id='$id'";

        $result1 = $conn->query($q);
      }
    }

    // Attempt to read property "num_rows" on true 
    if (!$result1) {
      echo "Preprocessing data gagal";
    } else {
      echo "Preprocessing data berhasil";
    }
  }
  ?>
  </table>
  </body>

  </html>


  <?php
  $content = ob_get_clean();
  include 'layout.php';
  ?>