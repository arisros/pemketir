<?php

$title = 'Form Upload';
ob_start();


?>

<section class="form">
  <!-- https://www.google.com/alerts/feeds/12246638679881655932/15333686624258242917 -->
  <h3>Tahap Pengumpulan Data</h3>
  <form action="load.php" method="GET">
    <input name="link" class="form-control" id="file" placeholder="https://google.com/alerts/feeds/....">
    <button type="submit" class="btn btn-primary btn-sm"><span class="bi-upload"></span>&nbsp;Upload</button>
  </form>


  <h3>Tahap Preprocessing</h3>
  <form action="preprocessing.php" method="GET">
    <button type="submit" class="btn btn-primary btn-sm"><span class="bi-gear"></span>&nbsp;Preprocessing</button>
  </form>

  <h3>Tahap Labelisasi</h3>
  <form action="labelisasi.php" method="GET">
    <button type="submit" class="btn btn-primary btn-sm"><span class="bi-tag"></span>&nbsp;Proses labelisasi</button>
  </form>

  <h3>Sentiment Analysis</h3>
  <form action="sentiment_process.php" method="GET">
    <button type="submit" class="btn btn-primary btn-sm"><span class="bi-emoji-smile"></span>&nbsp;Proses Sentimen Analysis</button>
  </form>

  <h3>Tahap Klasifikasi</h3>
  <form action="klasifikasi.php" method="GET">
    <button type="submit" class="btn btn-primary btn-sm"><span class="bi-layers"></span>&nbsp;Proses Klasifikasi</button>
  </form>

  <h3>Tahap Pengujian</h3>
  <form action="klasifikasi.php" method="GET">
    <a href="input_data_test.php">Input Data Test</a>
    <button type="submit">proses klasifikasi data uji</button>
  </form>
</section>

<?php
$content = ob_get_clean();
include 'layout.php';
?>