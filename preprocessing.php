<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include 'bootstrap.php'; ?>
  <title>Processing</title>
</head>

<body>
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

      <?php
      while ($d = mysqli_fetch_array($result)) {
        $id = $d['entry_id'];
        $content = $d['entry_content'];

        // 1 Case Folding
        // echo strtoupper$(content);;
        $cf = strtolower(($content));

        // Penghapusan Simbol-Simbol (SYmbol Removal)
        $simbol = preg_replace('/[^a-zA-Z\s]/', '', $cf);

        // Konversi Slangwod
        $rem_slang = explode(' ', $simbol);
        $slangword = str_replace(array_keys($arr_slang), $arr_slang, $simbol);

        // Stopword Removal
        $rem_stopword = $rem_slang; //rem_slang is already an array
        $str_data = array();
        foreach ($rem_stopword as $word) {
          if (!in_array($word, $stopwords)) {
            $str_data[] = $word;
          }
        }

        $stopword = implode(' ', $str_data);

        // Stemming
        $q1 = implode(' ', (array)$str_data);
        $stemming = $stemmer->stem($q1);

        // Tokenisasi
        $tokenisasi = preg_split('/\s+/', $stemming);
        $tokenisasi = implode(' ', $tokenisasi);


      ?>
        <tr bg-color="#ddd">
          <td><?php echo $id; ?></td>
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