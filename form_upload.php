<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=Form, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <?php include 'bootstrap.php'; ?>
  <title>Form Input File</title>
</head>

<body>
  <section class="form">
    <!-- https://www.google.com/alerts/feeds/12246638679881655932/15333686624258242917 -->
    <h3>Tahap Pengumpulan Data</h3>
    <form action="load.php" method="GET">
      <input name="link" id="file" placeholder="https://google.com/alerts/feeds/....">
      <button type="submit">Upload</button>
    </form>


    <h3>Tahap Preprocessing</h3>
    <form action="preprocessing.php" method="GET">
      <button type="submit">Preprocessing</button>
    </form>

    <h3>Tahap Labelisasi</h3>
    <form action="labelisasi.php" method="GET">
      <button type="submit">proses labelisasi</button>
    </form>

    <h3>Tahap Klasifikasi</h3>
    <form action="klasifikasi.php" method="GET">
      <button type="submit">proses klasifikasi</button>
    </form>

    <h3>Tahap Pengujian</h3>
    <form action="klasifikasi.php" method="GET">
      <button type="submit">input data uji</button>
      <button type="submit">proses klasifikasi data uji</button>
    </form>
  </section>
</body>

</html>